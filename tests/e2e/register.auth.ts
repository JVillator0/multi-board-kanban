import { test as setup, expect } from '@playwright/test';

const userFile = 'tests/e2e/.auth/user.json';

setup('register', async ({ page }) => {
    const currentTimestamp = new Date().getTime();

    await page.goto('/register');
    await page.getByRole('textbox', { name: 'Name' }).fill('Test Playwright');
    await page.getByRole('textbox', { name: 'Email' }).fill(`test.playwright+${currentTimestamp}@example.com`);
    await page.getByRole('textbox', { name: 'Password', exact: true }).fill('password');
    await page.getByRole('textbox', { name: 'Confirm Password' }).fill('password');
    await page.getByRole('button', { name: 'Register' }).click();

    await expect(page.getByRole('link', { name: 'Boards' })).toBeVisible();
    await expect(page.getByRole('heading', { name: 'Boards', exact: true })).toBeVisible();
    await expect(page.getByRole('heading', { name: 'My Boards (0)' })).toBeVisible();
    await expect(page.getByRole('heading', { name: 'Shared Boards (0)' }).locator('span')).toBeVisible();

    await page.context().storageState({ path: userFile });
});
