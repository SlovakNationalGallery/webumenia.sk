export function formatAuthorName(value) {
    return value.replace(/^([^,]*),\s*(.*)$/, '$2 $1')
}
