import { test, expect } from '@playwright/test';

test.describe('Board', () => {
    test.use({ storageState: 'tests/e2e/.auth/user.json' });

    test('Manage cards', async ({ page }) => {
        await page.goto('/boards');

        await test.step('Create', async () => {
            await page.getByRole('link', { name: 'Create Board' }).click();

            await page.getByRole('textbox', { name: 'Title' }).fill('Playwright Test Board');
            await page.getByRole('textbox', { name: 'Description' }).fill('Test Board for playwright testing');
            await page.getByRole('button', { name: 'Create' }).click();

            await expect(page.getByText('Board saved successfully')).toBeVisible();
            await page.getByTestId('close-notification').click();

            await expect(page.getByRole('heading', { name: 'Playwright Test Board' })).toBeVisible();
            await expect(page.getByText('Test Board for playwright')).toBeVisible();

            await page.getByTestId('board-options').click();
            await expect(page.getByRole('main')).toContainText('Edit Board');
            await expect(page.getByRole('main')).toContainText('Manage Members');
            await expect(page.getByRole('main')).toContainText('Delete Board');
        });

        await test.step('Edit', async () => {
            await page.getByRole('link', { name: 'Edit Board' }).click();

            await page.getByRole('textbox', { name: 'Title' }).fill('Playwright Test Board Updated');
            await page.getByRole('textbox', { name: 'Description' }).fill('Test Board for playwright testing Updated');
            await page.getByRole('button', { name: 'Update' }).click();

            await expect(page.getByText('Board saved successfully')).toBeVisible();
            await page.getByTestId('close-notification').click();

            await expect(page.getByRole('main')).toContainText('Test Board for playwright testing Updated');
            await expect(page.getByRole('main')).toContainText('Playwright Test Board Updated');
        });

        await test.step('Delete', async () => {
            await page.getByTestId('board-options').click();
            await page.getByRole('button', { name: 'Delete Board' }).click();
            await expect(page.getByRole('heading', { name: 'Delete Board' })).toBeVisible();
            await expect(page.getByText('Are you sure you want to')).toBeVisible();
            await expect(page.getByRole('button', { name: 'Confirm' })).toBeVisible();
            await page.getByRole('button', { name: 'Confirm' }).click();

            await expect(page.getByText('Board deleted successfully')).toBeVisible();
            await page.getByTestId('close-notification').click();
        });
    });
});
