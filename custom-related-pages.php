<div class="related-beaches-wrap">

    <h2 class="related-beaches-title">Related Pages</h2>

    <div class="related-beaches-grid">

        <a class="related-beach-card" href="/north-padre-island-developments/whitecap-npi">
            <div class="related-beach-thumb">
                <img src="https://www.simplypadre.com/images/pexel-photo-31663442-large.jpeg" alt="Whitecap NPI">
            </div>
            <div class="related-beach-body">
                <p class="related-beach-name">Whitecap NPI</p>
                <p class="related-beach-desc">Lorem ipsum dolor sit amet, consectetur adipiscing ultricies elit.</p>
            </div>
        </a>

		<a class="related-beach-card" href="/north-padre-island-developments/lake-padre-village">
            <div class="related-beach-thumb">
                <img src="https://www.simplypadre.com/images/pexel-photo-31663442-large.jpeg" alt="Lake Padre Village">
            </div>
            <div class="related-beach-body">
                <p class="related-beach-name">Lake Padre Village</p>
                <p class="related-beach-desc">Lorem ipsum dolor sit amet, consectetur adipiscing ultricies elit.</p>
            </div>
        </a>

        <a class="related-beach-card" href="/north-padre-island-developments/padre-island-mobility-plan">
            <div class="related-beach-thumb">
                <img src="https://www.simplypadre.com/images/pexel-photo-31663442-large.jpeg" alt="Padre Island Mobility Plan">
            </div>
            <div class="related-beach-body">
                <p class="related-beach-name">Padre Island Mobility Plan</p>
                <p class="related-beach-desc">Lorem ipsum dolor sit amet, consectetur adipiscing ultricies elit.</p>
            </div>
        </a>

        <a class="related-beach-card" href="/north-padre-island-developments/sandbox-beach-bar">
            <div class="related-beach-thumb">
                <img src="https://www.simplypadre.com/images/pexel-photo-31663442-large.jpeg" alt="Sandbox Beach bar">
            </div>
            <div class="related-beach-body">
                <p class="related-beach-name">Sandbox Beach bar</p>
                <p class="related-beach-desc">Lorem ipsum dolor sit amet, consectetur adipiscing ultricies elit.</p>
            </div>
        </a>

    </div>

    <hr class="related-beaches-divider">
    <a class="related-beaches-viewall" href="/north-padre-island-developments">View all developments →</a>

</div>
.related-beaches-wrap {
    margin-top: 32px;
}
.related-beaches-title {
    font-family: 'Playfair Display', serif;
    font-size: 2.1rem;
    font-weight: 600;
    text-align: left;
    margin-bottom: 20px;
    color: inherit;
}
.related-beaches-grid {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.related-beach-card {
    display: flex;
    gap: 14px;
    align-items: flex-start;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid rgba(0, 0, 0, 0.08);
    background: #fff;
    text-decoration: none;
    color: inherit;
    transition: border-color 0.2s;
}
.related-beach-card:hover {
    border-color: rgba(0, 0, 0, 0.18);
    text-decoration: none;
    color: inherit;
}
.related-beach-thumb {
    width: 80px;
    height: 62px;
    border-radius: 6px;
    flex-shrink: 0;
    overflow: hidden;
}
.related-beach-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.related-beach-body {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 4px;
}
.related-beach-name {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
    color: inherit;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.related-beach-desc {
    font-size: 12px;
    color: #666;
    margin: 0;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.related-beaches-divider {
    border: none;
    border-top: 1px solid rgba(0, 0, 0, 0.08);
    margin: 16px 0;
}
.related-beaches-viewall {
    display: block;
    text-align: center;
    font-size: 13px;
    color: #666;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid rgba(0, 0, 0, 0.08);
    text-decoration: none;
    transition: background 0.15s;
}
.related-beaches-viewall:hover {
    background: #f9f9f9;
    text-decoration: none;
    color: #444;
}

@media (max-width: 768px) {
    .related-beach-thumb {
        width: 68px;
        height: 54px;
    }
}
