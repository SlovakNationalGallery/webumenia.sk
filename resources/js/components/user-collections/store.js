const defaultState = {
  items: [],
}

const store = {
  state: JSON.parse(localStorage.getItem('userCollections')) || defaultState,

  getItems() {
    return this.state.items
  },

  addItem(itemId) {
    this.state.items.push({id: itemId})
    this._saveState()
    this._track('addItem', itemId)
  },

  removeItem(itemId) {
    this.state.items = this.state.items.filter(({id}) => id !== itemId)
    this._saveState()
    this._track('removeItem', itemId)
  },

  hasItem(itemId) {
    return this.state.items.findIndex(({id}) => id === itemId) > -1
  },

  toggleItem(itemId) {
    this.hasItem(itemId) ? this.removeItem(itemId) : this.addItem(itemId)
  },

  clearAllItems() {
    this.state.items = []
    this._saveState()
    this._track('clearAllItems', null)
  },

  _saveState() {
    localStorage.setItem('userCollections', JSON.stringify(this.state))
  },

  // GTM Tracking
  _track(actionName, itemId) {
    if (!window.dataLayer) return

    window.dataLayer.push({
      'event': 'UserCollectionEvent',
      'eventCategory': 'UserCollection',
      'eventAction': actionName,
      'eventLabel': itemId,
      'userCollection': this.state.items
    })
  }
}

module.exports = store
