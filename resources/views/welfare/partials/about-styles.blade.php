<style>
/* About Page Specific Premium Styling */

.section-padding {
    padding: 70px 0;
}
.bg-white {
    background-color: #ffffff;
}
.bg-light {
    background-color: #f9f9f9;
}
.bg-warm {
    background-color: #fcf6f3;
}

/* Who We Are Layout */
.who-we-are-grid {
    display: grid;
    grid-template-columns: 1.15fr 0.85fr;
    gap: 50px;
    align-items: center;
}
.who-headline {
    font-size: 30px;
    font-weight: 700;
    color: var(--color-heading);
    margin-bottom: 12px;
    line-height: 1.25;
}
.who-subheadline {
    font-size: 17px;
    font-weight: 600;
    color: var(--color-primary);
    margin-bottom: 25px;
    line-height: 1.5;
}
.who-body p {
    font-size: 14.5px;
    line-height: 24px;
    color: #555;
    margin-bottom: 18px;
}
.who-image img {
    width: 100%;
    border-radius: 6px;
    box-shadow: var(--shadow);
}

/* President's Note Layout */
.president-grid {
    display: grid;
    grid-template-columns: 0.8fr 1.2fr;
    gap: 45px;
    align-items: flex-start;
}
.president-img-container {
    position: relative;
    border-radius: 6px;
    overflow: hidden;
    box-shadow: var(--shadow);
}
.president-img-container img {
    width: 100%;
    display: block;
}
.president-title-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.85));
    color: #fff;
    padding: 15px;
    text-align: center;
}
.president-title-overlay h4 {
    margin: 0 0 3px 0;
    font-size: 15px;
    color: #fff;
    font-weight: 700;
}
.president-title-overlay span {
    font-size: 12px;
    opacity: 0.9;
}
.president-letter {
    background: #fff;
    border: 1px solid var(--color-border);
    border-radius: 6px;
    padding: 35px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
}
.president-letter h3 {
    font-size: 24px;
    margin-bottom: 20px;
    color: var(--color-heading);
    border-bottom: 2px solid var(--color-primary);
    padding-bottom: 8px;
    display: inline-block;
}
.president-letter > p {
    font-size: 14.5px;
    line-height: 24px;
    color: #444;
    margin-bottom: 15px;
    text-align: justify;
}
.president-signature {
    margin-top: 25px;
    border-top: 1px solid var(--color-border);
    padding-top: 15px;
}
.president-signature h5 {
    margin: 0;
    font-size: 15px;
    color: var(--color-heading);
}
.president-signature p {
    margin: 3px 0 0 0;
    font-size: 12px;
    color: #777;
}

/* Leadership Section Layout */
.leadership-header {
    text-align: center;
    max-width: 800px;
    margin: 0 auto 40px;
}
.leadership-header h2 {
    font-size: 30px;
    margin-bottom: 10px;
    color: var(--color-heading);
}
.leadership-header .subtitle {
    font-size: 17px;
    color: var(--color-primary);
    font-weight: 600;
    margin-bottom: 15px;
}
.leadership-header .intro-text {
    font-size: 14.5px;
    line-height: 22px;
    color: #666;
}

.leadership-tabs {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-bottom: 35px;
    border-bottom: 2px solid var(--color-border);
    padding-bottom: 1px;
    flex-wrap: wrap;
}
.leadership-tab-btn {
    background: none;
    border: none;
    font-family: var(--font-main);
    font-size: 15px;
    font-weight: 600;
    color: var(--color-text);
    cursor: pointer;
    padding: 10px 18px;
    position: relative;
    transition: color var(--transition);
}
.leadership-tab-btn:hover {
    color: var(--color-primary);
}
.leadership-tab-btn.active {
    color: var(--color-primary) !important;
}
.leadership-tab-btn.active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--color-primary);
}

.leadership-tab-content {
    display: none;
    animation: fadeInTab 0.4s ease forwards;
}
.leadership-tab-content.active {
    display: block;
}

.tab-intro {
    text-align: center;
    max-width: 750px;
    margin: 0 auto 35px;
    font-size: 14px;
    line-height: 22px;
    color: #555;
    font-style: italic;
    background: #fcfcfc;
    padding: 12px 20px;
    border-left: 3px solid var(--color-primary);
    border-radius: 0 4px 4px 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}

/* Members Grid Layout */
.members-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
}
.members-grid.exco-grid {
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

/* Member Card Layout */
.member-card {
    background: #ffffff;
    border: 1px solid var(--color-border);
    border-radius: 6px;
    padding: 22px 18px;
    text-align: center;
    transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
    position: relative;
    overflow: hidden;
}
.member-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-hover);
    border-color: var(--color-primary);
}
.member-avatar {
    width: 85px;
    height: 85px;
    border-radius: 50%;
    margin: 0 auto 12px;
    overflow: hidden;
    border: 3px solid #f3f3f3;
    transition: border-color var(--transition);
}
.member-card:hover .member-avatar {
    border-color: var(--color-primary);
}
.member-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.member-name {
    font-size: 14.5px;
    font-weight: 700;
    color: var(--color-heading);
    margin-bottom: 5px;
    line-height: 1.35;
}
.member-role {
    font-size: 12.5px;
    font-weight: 600;
    color: var(--color-primary);
    margin-bottom: 4px;
    display: block;
}
.member-org {
    font-size: 11.5px;
    color: #666;
    line-height: 1.35;
    display: block;
}
.member-tag {
    display: inline-block;
    background: #f1f5f9;
    color: #475569;
    font-size: 10.5px;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 12px;
    margin-top: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.member-card:hover .member-tag {
    background: var(--color-primary);
    color: #fff;
}

@keyframes fadeInTab {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 1199px) {
    .members-grid, .members-grid.exco-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
@media (max-width: 991px) {
    .who-we-are-grid, .president-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    .members-grid, .members-grid.exco-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 575px) {
    .members-grid, .members-grid.exco-grid {
        grid-template-columns: 1fr;
    }
}
</style>
