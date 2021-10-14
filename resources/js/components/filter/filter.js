function addParameterToQuery(name, value) {
  const url = new URL(window.location.href)
  value ? url.searchParams.set(name, value) : url.searchParams.delete(name)

  // Reset page on filter change
  url.searchParams.delete('page')

  window.location.href = url
}

module.exports = {
  addParameterToQuery
}
