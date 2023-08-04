import { test as setup, expect } from '@playwright/test'
import { STORAGE_STATE } from '../../playwright.config'

setup('authenticate', async ({ page }) => {
    await page.goto('/login')
    await page.fill('input[name="username"]', 'admin')
    await page.fill('input[name="password"]', 'admin')
    await page.click('input[type="submit"]')
    await page.context().storageState({ path: STORAGE_STATE })
})
