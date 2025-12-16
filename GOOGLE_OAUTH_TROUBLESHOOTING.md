# Google OAuth Error Fix: "Can't continue with google.com"

## The Problem
You're seeing the error: **"Can't continue with google.com. Something went wrong"** when trying to sign in.

This happens because Google Cloud Console needs to have BOTH `localhost` AND `127.0.0.1` configured.

## Solution: Update Google Cloud Console

### Step 1: Go to Google Cloud Console
1. Visit: https://console.cloud.google.com/
2. Select your project
3. Go to **APIs & Services** > **Credentials**

### Step 2: Edit Your OAuth 2.0 Client ID
1. Click on your OAuth 2.0 Client ID (the one with Client ID: `562488646908-ksfm89ce435o22c8l7e9fu6mt9p49d3u`)
2. Click **Edit** (pencil icon)

### Step 3: Add Authorized JavaScript Origins
In the **Authorized JavaScript origins** section, make sure you have ALL of these:
```
http://localhost:8000
http://127.0.0.1:8000
http://localhost
http://127.0.0.1
```

### Step 4: Add Authorized Redirect URIs
In the **Authorized redirect URIs** section, make sure you have ALL of these:
```
http://localhost:8000/auth/google/callback
http://127.0.0.1:8000/auth/google/callback
```

### Step 5: Save Changes
Click **Save** at the bottom

### Step 6: Wait a Few Minutes
Google's changes can take 1-5 minutes to propagate. Wait a bit, then try again.

## Alternative: Use localhost Instead of 127.0.0.1

If you want to avoid this issue, always access your site via:
```
http://localhost:8000
```
Instead of:
```
http://127.0.0.1:8000
```

## Verify Your Configuration

After making changes, verify:
1. ✅ Authorized JavaScript origins includes both localhost and 127.0.0.1
2. ✅ Authorized redirect URIs includes both localhost and 127.0.0.1
3. ✅ OAuth consent screen is configured (if required)
4. ✅ You've waited a few minutes for changes to propagate

## Still Not Working?

1. **Clear browser cache** and cookies for the site
2. **Check browser console** (F12) for any JavaScript errors
3. **Verify your .env file** has the correct Client ID:
   ```
   GOOGLE_CLIENT_ID=562488646908-ksfm89ce435o22c8l7e9fu6mt9p49d3u.apps.googleusercontent.com
   ```
4. **Restart your Laravel server**:
   ```bash
   php artisan serve
   ```
5. **Clear Laravel config cache**:
   ```bash
   php artisan config:clear
   ```


