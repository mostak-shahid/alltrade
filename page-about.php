<?php get_header();

$page_title = 'אודות';
$is_image = true;

set_query_var('page_title', $page_title);
set_query_var('is_image', $is_image);

get_template_part('content', 'page-header');

?>

<div style="background-image: url(	<?php echo wp_get_attachment_image_src(159, 'full')[0]; ?>);" class="about-content-container">
    <div class="about-content-container__logo">
        <img loading="lazy" alt="about-us" src="<?php echo wp_get_attachment_image_src(160, 'full')[0]; ?>" />
    </div>
    <div class="about-content-container__content">
        <p>
            חברת GET DEAL מקבוצת ALLTRADE מספקת פתרונות חומרה ברמה גבוהה וידידותית לסביבה דרך ביצוע שימוש חוזר למוצרי מחשוב ומתן שירות ואחריות מלאים לאחר המכירה.
            חברת GET DEAL מספקת ללקוחותיה חומרה מחודשת כגון - מחשבים נייחים וניידים, טאבלטים, מסכי מחשב סלולר ועוד...


            אנו ב GET DEAL - שמים את הלקוח וצרכיו במרכז ע"י התאמת פתרונות אישיים ליווי ותמיכה.
            לחברת GET DEAL קשרים ענפים עם יצרניות המחשוב המובילות וכן קווי ייצור ואריזה מהמתקדמים בשוק לציוד מחשוב.החומרה המגיעה למעבדות שלנו עוברת תהליך חידוש מוקפד ואיכותי (ISO 9001) ובדיקות איכות מחמירות בטרם מכירתה. כמו כן, כל החומרה המסופקת על ידי GET DEAL מלווה באחריות מלאה, ושירותי מעבדה מלאים.תחום המחשוב המחודש לא רק מוזיל את עלויות רכיבי המחשוב בעשרות אחוזים, הוא גם תורם לאיכות הסביבה הודות למחזור שנעשה ברכיבים הישנים וכל זה מבלי להתפשר על איכות החומרה, השירות והאחריות.

            אנו מזמינים אותך לבקר באתרי הקבוצה ולהתרשם מהפתרונות שאנו מציעים:
            www.alltrade.co.il
            www.allrecycling.co.il

            צוות השירות שלנו זמין לכל שאלה בטלפון *2277 או במייל service@get-deal.com
        </p>
    </div>
</div>

<?php get_template_part('content', 'choose-us'); ?>


<?php get_template_part('content', 'company-carousel'); ?>


<a href="#">
    <div style="background-image: url(	<?php echo wp_get_attachment_image_src(48, 'full')[0]; ?>);" class="bottom-banner">
    </div>
</a>

<a href="#">
    <div class="bottom-video">
        <img loading="lazy" alt="<?php echo get_the_title(161) ?>" src="<?php echo wp_get_attachment_image_src(161, 'full')[0]; ?>" />

    </div>
</a>


<?php

get_footer();
