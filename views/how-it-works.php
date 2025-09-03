<?php
$title = 'How It Works - OneNest';

ob_start();
?>

<section id="page-hire" class="page active">
    <div class="page-header">
        <h1>How It Works</h1>
        <p>A simple, safe, and secure process to get you started.</p>
    </div>

    <div class="container">
        <div class="steps-container">
            <div class="step">
                <div class="step-icon"><i data-lucide="user-check"></i></div>
                <h4>1. Join & Verify</h4>
                <p>Create an account with your phone number and complete a one-time identity verification using Omang or a valid ID.</p>
            </div>
            <div class="step-arrow"><i data-lucide="chevron-right"></i></div>
            <div class="step">
                <div class="step-icon"><i data-lucide="search"></i></div>
                <h4>2. Discover & Connect</h4>
                <p>Browse verified listings, post a job, or find a business. Connect securely through our internal messaging system.</p>
            </div>
            <div class="step-arrow"><i data-lucide="chevron-right"></i></div>
            <div class="step">
                <div class="step-icon"><i data-lucide="star"></i></div>
                <h4>3. Transact & Review</h4>
                <p>Arrange payment and delivery directly. Afterwards, leave a review to help build our trusted community.</p>
            </div>
        </div>
        
        <div class="faq-section">
            <h2 class="section-title" style="margin-top: 80px; margin-bottom: 40px;">Frequently Asked Questions</h2>
            <div class="faq-item">
                <h3>Why do I need to verify my ID?</h3>
                <p>Verification is the cornerstone of trust on OneNest. It ensures all members are real people, which drastically reduces scams and builds a safer community for everyone.</p>
            </div>
            <div class="faq-item">
                <h3>Is OneNest free to use?</h3>
                <p>Yes, joining, browsing, and listing items is free for all members. We may introduce premium features for businesses or high-volume professionals in the future.</p>
            </div>
            <div class="faq-item">
                <h3>How are payments handled?</h3>
                <p>Currently, OneNest facilitates the connection. Payment and delivery terms are arranged directly between the buyer and seller or the client and professional. Always follow safe transaction practices.</p>
            </div>
        </div>
    </div>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/app.php';
?>