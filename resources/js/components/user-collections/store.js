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
  },

  removeItem(itemId) {
    this.state.items = this.state.items.filter(({id}) => id !== itemId)
    this._saveState()
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
  },

  _saveState() {
    localStorage.setItem('userCollections', JSON.stringify(this.state))
  },
}

module.exports = store
