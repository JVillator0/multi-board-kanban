import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
  testDir: './tests/e2e',
  fullyParallel: true,
  timeout: 30000, // 30s
  retries: 2,
  workers: 1,
  use: {
    baseURL: process.env.PLAYWRIGHT_BASE_URL || 'http://localhost',
    headless: true,
    viewport: { width: 1650, height: 1050 },
    actionTimeout: 10000,
    navigationTimeout: 10000,
    trace: 'on',
    video: 'off',
    screenshot: 'only-on-failure',
    ignoreHTTPSErrors: false,
  },
  projects: [
    {
        name: 'setup',
        testMatch: /.*\.auth\.ts/
    },
    {
        name: 'general',
        use: {
            ...devices['Desktop Chrome'],
        },
        dependencies: ['setup'],
    },
  ],
});
