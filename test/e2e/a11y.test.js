/**
 * External dependencies
 */
import { test, expect } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';

test( 'should not have automatically detectable accessibility issues', async ( {
	page,
}, testInfo ) => {
	await page.goto( '/' );
	const accessibilityScanResults = await new AxeBuilder( { page } )
		.withTags( [ 'wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa' ] )
		.analyze();

	// Attach accessibility violations to test report.
	await testInfo.attach( 'accessibility-scan-results', {
		body: JSON.stringify( accessibilityScanResults.violations, null, 2 ),
		contentType: 'application/json',
	} );

	expect( accessibilityScanResults.violations ).toEqual( [] );
} );
