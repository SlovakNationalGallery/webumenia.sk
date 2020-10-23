// WEBUMENIA-1462
// This code is run from Google Tag Manager (GTM), not here directly. Keeping it here for version control purposes.
// TODO: Move this from GTM and into asset pipeline after experiments are done.

(function(){
// https://codeburst.io/throttling-and-debouncing-in-javascript-b01cad5c8edf
  function throttle(func, limit) {
    var inThrottle

    return function() {
      var args = arguments
      var context = this
      if (!inThrottle) {
        func.apply(context, args)
        inThrottle = true
        setTimeout(
          function() {
            inThrottle = false
          },
          limit
        )
      }
    }
  }

  function sendSoundCloudEvent(eventAction, eventLabel, relativeProgress) {
    window.dataLayer.push({
      'event': 'soundCloudEvent',
      'eventCategory': 'SoundCloud',
      'eventAction': eventAction,
      'eventLabel': eventLabel,
      'soundCloudPlayProgress': relativeProgress
    })
  }

  function initializeSoundCloudWidget(soundCloudIframe) {
    var widget = SC.Widget(soundCloudIframe)
    var label

    var progressReportPoints = [25, 50, 75, 100]
    var completedProgressReports = {}

    var positionPercent = 0
    var reportSeek = true; // SEEK event is fired also when paused

    widget.bind(SC.Widget.Events.READY, function() {
      // Get the title of the currently playing sound
      widget.getCurrentSound(function(cs) {
        label = cs["title"]
      })

      // 'Play' event is fired twice (bug?), so limit it
      widget.bind(SC.Widget.Events.PLAY, throttle(function(progress) {
        reportSeek = true

        sendSoundCloudEvent('Play', label, positionPercent)
      }, 1000))

      widget.bind(SC.Widget.Events.PAUSE, function(progress) {
        reportSeek = false

        if (positionPercent !== 100) {
          sendSoundCloudEvent('Pause', label, positionPercent)
        }
      })

      widget.bind(SC.Widget.Events.SEEK, function(progress) {
        if (!reportSeek) return

        // Use a timeout to capture tnew position, not the old one
        setTimeout(function() {
          sendSoundCloudEvent('Seek', label, positionPercent)
        }, 500)

      })

      // PLAY_PROGRESS gets called a lot, so limit it to one call per second
      widget.bind(SC.Widget.Events.PLAY_PROGRESS, throttle(function(progress) {
        positionPercent = Math.round(progress["relativePosition"] * 100)

        if (progressReportPoints.indexOf(positionPercent) > -1) {
          if (completedProgressReports[positionPercent] === true) return

          completedProgressReports[positionPercent] = true
          sendSoundCloudEvent('Progress ' + positionPercent + '%', label, positionPercent)
        }
      }, 1000))
    })
  }

  $(document).ready(function() {
    try {
      // For each SoundCloud iFrame, initiate the API integration
      var i, len
      var iframes = document.querySelectorAll('iframe[src*="api.soundcloud.com"]')
      for (i = 0, len = iframes.length; i < len; i += 1) {
        initializeSoundCloudWidget(iframes[i])
      }
    } catch (e) {
      console.log("Error with SoundCloud API: " + e.message)
    }
  })
})()
