import { test, expect } from '@playwright/test';

test.describe('Tasks', () => {
    test.use({ storageState: 'tests/e2e/.auth/user.json' });

    test('Manage tasks', async ({ page }) => {
        await page.goto('/boards');

        await test.step('Create board', async () => {
            await page.getByRole('link', { name: 'Create Board' }).click();

            await page.getByRole('textbox', { name: 'Title' }).fill('Playwright Test Card Management');
            await page.getByRole('textbox', { name: 'Description' }).fill('Test card management for playwright testing');
            await page.getByRole('button', { name: 'Create' }).click();

            await expect(page.getByText('Board saved successfully')).toBeVisible();
            await page.getByTestId('close-notification').click();

            await expect(page.getByRole('heading', { name: 'Playwright Test Card Management' })).toBeVisible();
            await expect(page.getByText('Test card management for playwright testing')).toBeVisible();
        });

        await test.step('Open Board', async () => {
            await page.getByRole('heading', { name: 'Playwright Test Card Management' }).click();

            await expect(page.getByRole('heading', { name: 'Playwright Test Card Management' })).toBeVisible();
            await expect(page.getByText('Test card management for playwright testing')).toBeVisible();
            await expect(page.getByRole('heading', { name: 'Backlog (0)' })).toBeVisible();
            await expect(page.getByRole('heading', { name: 'To Do (0)' })).toBeVisible();
            await expect(page.getByRole('heading', { name: 'In Progress (0)' })).toBeVisible();
            await expect(page.getByRole('heading', { name: 'Done (0)' })).toBeVisible();
        });

        await test.step('Create', async () => {
            await page.getByTestId('add-task-backlog').click();

            await page.getByRole('textbox', { name: 'Title' }).click();
            await page.getByRole('textbox', { name: 'Title' }).fill('Playwright Task for Testing');
            await page.getByRole('textbox', { name: 'Description' }).fill('Playwright testing for technical challenge');
            await page.getByLabel('Priority').selectOption('high');
            await page.getByLabel('Status').selectOption('todo');
            await page.getByRole('textbox', { name: 'Due Date' }).fill('2025-02-19');
            await page.getByRole('button', { name: 'Save' }).click();

            await expect(page.getByText('Task saved successfully!')).toBeVisible();
            await page.getByTestId('close-notification').click();

            await expect(page.getByRole('heading', { name: 'Backlog (0)' })).toBeVisible();
            await expect(page.getByRole('heading', { name: 'To Do (1)' })).toBeVisible();
            await expect(page.getByRole('heading', { name: 'In Progress (0)' })).toBeVisible();
            await expect(page.getByRole('heading', { name: 'Done (0)' })).toBeVisible();

            await expect(page.getByRole('main')).toContainText('Playwright Task for Testing');
            await expect(page.getByRole('main')).toContainText('Playwright testing for technical challenge');
            await expect(page.getByRole('main')).toContainText('HIGH');
            await expect(page.getByRole('main')).toContainText('Feb 18, 2025');
        });

        await test.step('View', async () => {
            await page.getByText('Playwright Task for Testing').click();
            await expect(page.getByRole('textbox', { name: 'Title' })).toHaveValue('Playwright Task for Testing');
            await expect(page.getByRole('textbox', { name: 'Title' })).toBeDisabled();

            await expect(page.getByRole('textbox', { name: 'Description' })).toHaveValue('Playwright testing for technical challenge');
            await expect(page.getByRole('textbox', { name: 'Description' })).toBeDisabled();

            await expect(page.getByLabel('Priority')).toHaveValue('high');
            await expect(page.getByLabel('Priority')).toBeDisabled();

            await expect(page.getByLabel('Status')).toHaveValue('todo');
            await expect(page.getByLabel('Status')).toBeDisabled();

            await expect(page.getByRole('textbox', { name: 'Due Date' })).toHaveValue('2025-02-19');
            await expect(page.getByRole('textbox', { name: 'Due Date' })).toBeDisabled();
        });

        await test.step('Edit', async () => {
            await page.getByTestId('modal-task-options').click();
            await page.getByRole('button', { name: 'Edit' }).click();

            await expect(page.getByRole('textbox', { name: 'Title' })).toBeEnabled();
            await expect(page.getByRole('textbox', { name: 'Description' })).toBeEnabled();
            await expect(page.getByLabel('Priority')).toBeEnabled();
            await expect(page.getByLabel('Status')).toBeEnabled();
            await expect(page.getByRole('textbox', { name: 'Due Date' })).toBeEnabled();

            await page.getByRole('textbox', { name: 'Title' }).fill('Playwright Task for Testing Updated');
            await page.getByRole('textbox', { name: 'Description' }).fill('Playwright testing for technical challenge Updated');
            await page.getByRole('button', { name: 'Save' }).click();

            await expect(page.getByText('Task saved successfully!')).toBeVisible();
            await page.getByTestId('close-notification').click();

            await expect(page.getByRole('main')).toContainText('Playwright Task for Testing Updated');
            await expect(page.getByRole('main')).toContainText('Playwright testing for technical challenge Updated');
        });

        await test.step('Delete', async () => {
            await page.getByTestId('task-options').click();
            await page.getByRole('button', { name: 'Delete' }).click();
            await expect(page.getByRole('heading', { name: 'Delete Task' })).toBeVisible();
            await expect(page.getByText('Are you sure you want to delete this task? This action cannot be undone.')).toBeVisible();

            await page.getByRole('button', { name: 'Confirm' }).click();
            await expect(page.getByText('Task deleted successfully!')).toBeVisible();
            await page.getByTestId('close-notification').click();
        });
    });
});
