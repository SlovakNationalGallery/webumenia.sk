export function formatAuthor(author) {
    return author.replace(/^([^,]*),\s*(.*)$/, '$2 $1')
}

export function formatNum(num, decimals) {
    return num
        .toLocaleString('en-US', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals,
            useGrouping: true,
        })
        .replace(/,/g, ' ')
}
