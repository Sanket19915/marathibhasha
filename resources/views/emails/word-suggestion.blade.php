<!DOCTYPE html>
<html lang="mr" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="font-family: 'Noto Sans Devanagari', Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #ea580c; border-bottom: 2px solid #ea580c; padding-bottom: 10px;">
            नवीन शब्द सुचना
        </h2>
        
        <div style="margin-top: 20px;">
            @if($name)
                <p><strong>नाव:</strong> {{ $name }}</p>
            @endif
            
            @if($email)
                <p><strong>ई-मेल:</strong> {{ $email }}</p>
            @endif
            
            <p><strong>शब्द:</strong> {{ $word }}</p>
            
            <p><strong>शब्दार्थ:</strong></p>
            <div style="background-color: #f9fafb; padding: 15px; border-radius: 5px; margin: 10px 0;">
                {{ $meaning }}
            </div>
            
            <p><strong>शब्दाचे स्रोत आणि संदर्भ:</strong></p>
            <div style="background-color: #f9fafb; padding: 15px; border-radius: 5px; margin: 10px 0;">
                {{ $sourceReference }}
            </div>
        </div>
        
        <p style="margin-top: 30px; color: #666; font-size: 14px;">
            ही सुचना marathibhasha.org वरून मिळाली आहे.
        </p>
    </div>
</body>
</html>


