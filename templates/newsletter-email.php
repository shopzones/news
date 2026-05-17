<?php
// templates/newsletter-email.php
function getNewsletterEmailTemplate($subject, $content) {
    return '
    <!DOCTYPE html>
    <html>
    <head><meta charset="UTF-8"></head>
    <body style="font-family: Arial, sans-serif; background: #f8f9fa; padding: 20px;">
        <div style="max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px;">
            <h2 style="color: #E50914;">' . htmlspecialchars($subject) . '</h2>
            <div style="line-height: 1.7; color: #333;">
                ' . $content . '
            </div>
            <hr style="margin: 30px 0;">
            <p style="font-size: 12px; color: #999;">You received this email because you subscribed to News Portal. 
            <a href="{{unsubscribe_link}}" style="color: #E50914;">Unsubscribe</a></p>
        </div>
    </body>
    </html>';
}
?>