@extends('welfare.layouts.app')

@section('title', 'Legal Disclaimer - Pertubuhan Gabungan MUKMIN Nasional')

@section('content')
<style>
.legal-disclaimer-page {
    font-family: var(--font-main);
    color: var(--color-heading);
}
.legal-disclaimer-card {
    background: #fff;
    border: 1px solid #eaeaea;
    border-radius: 10px;
    padding: 40px 36px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
}
.legal-disclaimer-card h2 {
    margin-bottom: 24px;
    text-align: left;
}
.legal-disclaimer-content {
    text-align: justify;
}
.legal-disclaimer-content p {
    margin-bottom: 18px;
    line-height: 1.8;
    color: #444;
}
.legal-disclaimer-content p:last-child {
    margin-bottom: 0;
}
</style>

<section class="section-padding bg-light legal-disclaimer-page" style="padding: 70px 0;">
    <div class="container" style="max-width: 960px;">
        <div class="legal-disclaimer-card">
            <h2>Legal Disclaimer</h2>

            <div class="legal-disclaimer-content">
                <p>
                    The information, materials and content made available on this website are provided for general informational and community engagement purposes only. Pertubuhan Gabungan MUKMIN Nasional (MUKMIN) reserves the right to modify, update or remove any content without prior notice.
                </p>

                <p>
                    While reasonable efforts are taken to ensure the accuracy and reliability of the information presented, MUKMIN makes no representations or warranties, express or implied, regarding the completeness, accuracy or suitability of the content for any purpose.
                </p>

                <p>
                    Any submissions, applications, registrations or requests made through this website are subject to internal review, verification and approval processes. Submission does not guarantee acceptance, approval, assistance, partnership or participation in any programme or initiative.
                </p>

                <p>
                    By using this website, users acknowledge and consent to the collection, processing and storage of personal data in accordance with applicable laws and MUKMIN&rsquo;s operational requirements. MUKMIN reserves the right to use submitted information, photographs and related materials for documentation, reporting, publicity and community engagement purposes where applicable.
                </p>

                <p>
                    MUKMIN shall not be held liable for any direct, indirect or consequential loss arising from the use of this website or reliance on its content.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
