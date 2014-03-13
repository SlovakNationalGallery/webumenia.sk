jQuery(document).ready(function($) {

jQuery("#lasttweet").tweet({
  join_text: false,
  username: "envato", // Change username here
  modpath: './js/twitter/',
  avatar_size: false,
  count: 1,
  loading_text: "loading tweets...",
  seconds_ago_text: "about %d seconds ago",
  a_minutes_ago_text: "about a minute ago",
  minutes_ago_text: "about %d minutes ago",
  a_hours_ago_text: "about an hour ago",
  hours_ago_text: "about %d hours ago",
  a_day_ago_text: "about a day ago",
  days_ago_text: "about %d days ago",
  view_text: "view tweet on twitter"
});

});