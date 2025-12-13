# Google Sign-In Setup

This document explains how to configure Google Sign-In (One Tap, Automatic sign-in, and Sign in with Google button) for the MarathiBhasha website.

## Environment Variables

Add the following to your `.env` file:

```env
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

**Note:** Update `GOOGLE_REDIRECT_URI` with your production domain when deploying.

## Important: Google Cloud Console Configuration

For Google Sign-In to work on **localhost**, you MUST configure the following in your Google Cloud Console:

### Step 1: Add Authorized JavaScript Origins
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Navigate to **APIs & Services** > **Credentials**
3. Click on your OAuth 2.0 Client ID
4. Under **Authorized JavaScript origins**, add:
   - `http://localhost:8000`
   - `http://127.0.0.1:8000`

### Step 2: Add Authorized Redirect URIs
Under **Authorized redirect URIs**, add:
   - `http://localhost:8000/auth/google/callback`
   - `http://127.0.0.1:8000/auth/google/callback`

### Step 3: Enable Google+ API (if needed)
1. Go to **APIs & Services** > **Library**
2. Search for "Google+ API" or "Google Identity Services"
3. Make sure it's enabled

## Testing on Localhost

1. **Make sure your `.env` file has the credentials** (see above)
2. **Clear config cache**:
   ```bash
   php artisan config:clear
   ```
3. **Start your Laravel server**:
   ```bash
   php artisan serve
   ```
4. **Visit** `http://localhost:8000`
5. **Check browser console** for any errors
6. **The Sign in with Google button should appear** on the home page
7. **One Tap may not appear on localhost** (Google restricts it), but the button will work

## Troubleshooting

### Button Not Showing
- Check browser console for JavaScript errors
- Verify `GOOGLE_CLIENT_ID` is set in `.env`
- Run `php artisan config:clear`
- Make sure the Google Sign-In SDK is loading (check Network tab)

### One Tap Not Appearing
- **This is normal on localhost** - Google One Tap has restrictions for localhost
- The "Sign in with Google" button will still work
- One Tap works better on production domains

### OAuth Error
- Verify redirect URI is whitelisted in Google Cloud Console
- Check that `GOOGLE_REDIRECT_URI` matches exactly in `.env` and Google Console
- Make sure the OAuth consent screen is configured

## Features Implemented

1. **One Tap Sign-In**: Automatically prompts users to sign in if they have an active Google session
2. **Automatic Sign-In**: Seamlessly signs in users with their Google account
3. **Sign in with Google Button**: Traditional button for manual sign-in

## How It Works

1. When a user visits the site, Google One Tap automatically detects if they have an active Google session
2. If detected, a prompt appears asking if they want to sign in
3. Users can also click the "Sign in with Google" button
4. After authentication, users are automatically logged into the website
5. User accounts are created automatically if they don't exist

## Database Changes

The following fields have been added to the `users` table:
- `google_id`: Stores the Google user ID
- `avatar`: Stores the user's Google profile picture URL
- `password`: Made nullable (users can sign in with Google without a password)

## Routes

- `GET /auth/google` - Redirects to Google OAuth
- `GET /auth/google/callback` - Handles Google OAuth callback
- `POST /auth/google/one-tap` - Handles One Tap authentication (JSON)

## Components

The Google Sign-In component (`<x-google-signin />`) can be added to any page where you want to display the sign-in option.

## Testing

1. Make sure your `.env` file has the correct Google credentials
2. Visit the home page
3. If you're logged into Google in your browser, you should see the One Tap prompt
4. You can also click the "Sign in with Google" button
