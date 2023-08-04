import { test, expect, Page } from '@playwright/test'

// https://github.com/microsoft/playwright/issues/620#issuecomment-578022596
async function scrollFullPageSlowly(page) {
    await page.evaluate(async () => {
        await new Promise<void>((resolve) => {
            let totalHeight = 0
            const distance = 100
            const timer = setInterval(() => {
                const scrollHeight = document.body.scrollHeight
                window.scrollBy(0, distance)
                totalHeight += distance

                if (totalHeight >= scrollHeight) {
                    clearInterval(timer)
                    resolve()
                }
            }, 100)
        })
    })
}

async function takeScreenShot(page: Page, options = { scrollSlowly: false }) {
    if (options.scrollSlowly) await scrollFullPageSlowly(page)

    await new Promise((resolve) => setTimeout(resolve, 100))

    await expect(page).toHaveScreenshot({
        maxDiffPixels: 400,
        fullPage: true,
        mask: [page.locator('css=[data-playwright-mask]')],
    })
}

async function visitAndScreenshot(page: Page, path: string, options = { scrollSlowly: true }) {
    await page.goto(path)
    await takeScreenShot(page, { scrollSlowly: options.scrollSlowly })
}

test.describe('visual regressions', () => {
    test('homepage', async ({ page }) => visitAndScreenshot(page, '/'))
    test('catalog', async ({ page }) => visitAndScreenshot(page, '/katalog'))

    test('collections index', async ({ page }) => visitAndScreenshot(page, '/kolekcie'))
    test('collection detail', async ({ page }) => visitAndScreenshot(page, '/kolekcia/176'))

    test('authors index', async ({ page }) => visitAndScreenshot(page, '/autori'))
    test('author detail', async ({ page }) => visitAndScreenshot(page, '/autor/11436'))

    test('articles index', async ({ page }) => visitAndScreenshot(page, '/clanky'))
    test('article detail', async ({ page }) =>
        visitAndScreenshot(page, '/clanok/peripersonal-space'))

    test('edu articles index', async ({ page }) => visitAndScreenshot(page, '/edu'))

    test('reproductions', async ({ page }) => visitAndScreenshot(page, '/reprodukcie')) // TODO handle random carousel

    test('info', async ({ page }) => visitAndScreenshot(page, '/informacie'))

    test('favourites empty', async ({ page }) => visitAndScreenshot(page, '/oblubene'))

    test('favourites filled', async ({ page }) =>
        visitAndScreenshot(page, '/oblubene?ids%5B%5D=SVK%3ASNG.O_1870'))

    test.describe('artwork', () => {
        test('detail', async ({ page }) => visitAndScreenshot(page, '/dielo/SVK:SNG.O_1870'))
        test('zoom', async ({ page }) => visitAndScreenshot(page, '/dielo/SVK:SNG.O_1870/zoom'))
    })

    test.describe('order', () => {
        test('empty', async ({ page }) => visitAndScreenshot(page, '/objednavka'))
        test('non-empty', async ({ page }) => {
            await page.goto('/dielo/SVK:SNG.O_1870')
            await page.getByText('objednať reprodukciu').click()
            await visitAndScreenshot(page, '/objednavka')
        })
    })

    // Non-public pages
    test.describe('admin', () => {
        test.skip('login screen', async ({ page }) => {
            await page.goto('/logout')
            // TODO logging out seems to disconnect session for other tests, so we skip for now
        })

        test('admin', async ({ page }) => visitAndScreenshot(page, '/admin'))
        test.describe('item', () => {
            test('index', async ({ page }) => visitAndScreenshot(page, '/item'))
            // TODO unreliable
            test.skip('show', async ({ page }) => {
                await page.goto('/item')
                await page.locator('tr', { hasText: 'SVK:SNG.O_1870' }).getByText('detail').click()
                await takeScreenShot(page)
            })
            test('edit', async ({ page }) => visitAndScreenshot(page, '/item/SVK:SNG.O_1870/edit'))
        })
        test.describe('authority', () => {
            test('index', async ({ page }) => visitAndScreenshot(page, '/authority'))
            test('edit', async ({ page }) => visitAndScreenshot(page, '/authority/1907/edit'))
        })
        test.describe('collection', () => {
            test('index', async ({ page }) => visitAndScreenshot(page, '/collection'))
            test('show', async ({ page }) => {
                await page.goto('/collection')
                await page.locator('tr', { hasText: '187' }).getByText('detail').click()
                await takeScreenShot(page)
            })
            test('edit', async ({ page }) => visitAndScreenshot(page, '/collection/187/edit'))
        })
        test.describe('article', () => {
            test('index', async ({ page }) => visitAndScreenshot(page, '/article'))
            test('edit', async ({ page }) => visitAndScreenshot(page, '/article/92/edit'))
        })
        test.describe('shuffled items', () => {
            test('index', async ({ page }) => visitAndScreenshot(page, '/shuffled-items'))
            test('create', async ({ page }) => visitAndScreenshot(page, '/shuffled-items/create'))
            test('edit', async ({ page }) => visitAndScreenshot(page, '/shuffled-items/16/edit'))
        })
        test.describe('featured-pieces', () => {
            test('index', async ({ page }) => visitAndScreenshot(page, '/featured-pieces'))
            test('create', async ({ page }) => visitAndScreenshot(page, '/featured-pieces/create'))
            test('edit', async ({ page }) => visitAndScreenshot(page, '/featured-pieces/4/edit'))
        })
        test.describe('featured-artworks', () => {
            test('index', async ({ page }) => visitAndScreenshot(page, '/featured-artworks'))
            test('create', async ({ page }) =>
                visitAndScreenshot(page, '/featured-artworks/create'))
            test('edit', async ({ page }) => visitAndScreenshot(page, '/featured-artworks/17/edit'))
        })
        test('notices', async ({ page }) => visitAndScreenshot(page, '/notices/1/edit'))
    })
})
