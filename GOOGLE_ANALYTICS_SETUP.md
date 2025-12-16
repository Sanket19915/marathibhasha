# Google Analytics Setup

This document explains how to configure Google Analytics (GA4) for marathibhasha.org.

## Prerequisites

1. A Google Analytics 4 (GA4) property set up in your Google Analytics account
2. Access to the Measurement ID (format: `G-XXXXXXXXXX`)

## Setup Instructions

### Step 1: Create a Google Analytics 4 Property

1. Go to [Google Analytics](https://analytics.google.com/)
2. Sign in with your Google account
3. Click **Admin** (gear icon) in the bottom left
4. In the **Property** column, click **Create Property**
5. Enter property name: **MarathiBhasha**
6. Select your reporting time zone and currency
7. Click **Next** and complete the business information
8. Click **Create**
9. Select **Web** as your platform
10. Enter website URL: **https://marathibhasha.org**
11. Enter stream name: **marathibhasha.org**
12. Click **Create stream**
13. Copy the **Measurement ID** (starts with `G-`)

### Step 2: Configure Environment Variable

Add the following to your `.env` file:

```env
GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX
```

Replace `G-XXXXXXXXXX` with your actual Measurement ID from Step 1.

**For local development:**
- You can leave this empty or commented out to disable tracking on localhost
- Analytics will only work on the production domain (marathibhasha.org)

**For production (marathibhasha.org):**
- Add the Measurement ID to your production `.env` file
- Make sure the domain is verified in Google Analytics

### Step 3: Verify Configuration

1. **Clear config cache** (if needed):
   ```bash
   php artisan config:clear
   ```

2. **Visit your website** (marathibhasha.org)

3. **Check Google Analytics**:
   - Go to Google Analytics dashboard
   - Navigate to **Reports** > **Realtime**
   - You should see your visit appear within a few seconds

### Step 4: Configure Domain in Google Analytics

1. In Google Analytics, go to **Admin** > **Data Streams**
2. Click on your web stream
3. Under **Web stream details**, make sure:
   - **Website URL** is set to `https://marathibhasha.org`
   - **Default URL** is set to `https://marathibhasha.org`

## What Gets Tracked

The following events are automatically tracked:

- **Page views**: Every page visit
- **Page titles**: Dynamic page titles
- **Page paths**: Full URL paths including query parameters
- **User interactions**: Clicks, scrolls, and other interactions (if enabled)

## Testing

### On Localhost
- Google Analytics will **not** track localhost visits by default
- This is expected behavior to avoid polluting your analytics data
- To test, you can temporarily add `localhost` to your Google Analytics property, but it's recommended to test on the production domain

### On Production (marathibhasha.org)
- All page views and user interactions will be tracked
- Data will appear in your Google Analytics dashboard within 24-48 hours for standard reports
- Real-time data appears immediately in the Realtime report

## Troubleshooting

### Analytics Not Working

1. **Check Measurement ID**:
   - Verify `GOOGLE_ANALYTICS_ID` is set in `.env`
   - Make sure there are no extra spaces or quotes
   - Format should be: `G-XXXXXXXXXX`

2. **Clear Config Cache**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

3. **Check Browser Console**:
   - Open browser DevTools (F12)
   - Go to Console tab
   - Look for any JavaScript errors
   - Check Network tab to see if `gtag.js` is loading

4. **Verify Domain**:
   - Make sure the domain in Google Analytics matches `marathibhasha.org`
   - Check that the property is a GA4 property (not Universal Analytics)

5. **Check Google Analytics Dashboard**:
   - Go to **Admin** > **Data Streams**
   - Verify the stream is active and receiving data
   - Check the **Realtime** report to see if data is coming in

### Still Not Working?

- Make sure you're using **Google Analytics 4 (GA4)**, not Universal Analytics
- Universal Analytics (UA) has been deprecated and uses a different format (`UA-XXXXXXXXX-X`)
- This implementation only supports GA4 Measurement IDs (`G-XXXXXXXXXX`)

## Additional Configuration

### Enhanced Ecommerce (Optional)

If you want to track ecommerce events, you can add custom event tracking in your views:

```javascript
gtag('event', 'purchase', {
    'transaction_id': '12345',
    'value': 29.99,
    'currency': 'INR',
    'items': [{
        'item_name': 'Product Name',
        'price': 29.99,
        'quantity': 1
    }]
});
```

### Custom Events (Optional)

You can track custom events throughout your application:

```javascript
gtag('event', 'search', {
    'search_term': 'user_search_query'
});
```

## Security Notes

- The Measurement ID is public and visible in the page source
- This is normal and expected behavior for Google Analytics
- The Measurement ID alone cannot be used to access your Analytics account
- Never share your Google Analytics account credentials

## Support

For more information, visit:
- [Google Analytics Help Center](https://support.google.com/analytics)
- [GA4 Documentation](https://developers.google.com/analytics/devguides/collection/ga4)

