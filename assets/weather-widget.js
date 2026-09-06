const texts = JSON.parse(document.body.dataset.texts || '{}');
const t = text => texts[text] ?? text;

import {feedURL, subscribe} from '../../assets/feed-client.js';

const statusText = {ready: '', stale: t('Stale'), pending: t('Pending'), unavailable: t('No data')};

class WeatherWidget extends HTMLElement {
    static observedAttributes = ['api', 'fields', 'title'];
    connectedCallback() { this.start(); }
    disconnectedCallback() { this.stop?.(); }
    attributeChangedCallback() { if (this.isConnected) this.start(); }

    start() {
        this.stop?.();
        const root = this.shadowRoot || this.attachShadow({mode: 'open'});
        root.replaceChildren();
        const style = document.createElement('link');
        style.rel = 'stylesheet';
        style.href = new URL('../../assets/weather-widget.css', import.meta.url).href;
        const box = document.createElement('section');
        const title = document.createElement('h2');
        const list = document.createElement('dl');
        const status = document.createElement('p');
        status.setAttribute('role', 'status');
        status.textContent = t('Loading …');
        box.append(title, list, status);
        root.append(style, box);
        try {
            const url = new URL(feedURL(this.getAttribute('api') || ''));
            const selection = this.getAttribute('fields');
            if (selection) url.searchParams.set('fields', selection);
            this.stop = subscribe(url.href, (feed, error) => {
                if (feed) {
                    title.textContent = this.getAttribute('title') || feed.title || t('Weather');
                    list.replaceChildren();
                    for (const value of Object.values(feed.data)) {
                        if (!value || value.type !== 'value') continue;
                        const row = document.createElement('div');
                        const term = document.createElement('dt');
                        const description = document.createElement('dd');
                        const number = document.createElement('strong');
                        const stamp = document.createElement('small');
                        term.textContent = t(String(value.label || 'Value'));
                        number.textContent = typeof value.formatted === 'string' ? value.formatted : '—';
                        const date = typeof value.asOf === 'number' ? new Intl.DateTimeFormat(document.documentElement.lang || 'en', {
                            timeZone: feed.timezone || 'UTC', day: '2-digit', month: '2-digit', year: 'numeric',
                            hour: '2-digit', minute: '2-digit', second: '2-digit', timeZoneName: 'short',
                        }).format(new Date(value.asOf * 1000)) : '';
                        stamp.textContent = [{live: 'Live', archive: t('Archive'), astronomy: t('Astronomy')}[value.source] || '', date,
                            statusText[value.status] || ''].filter(Boolean).join(' · ');
                        description.append(number, stamp);
                        row.append(term, description);
                        list.append(row);
                    }
                }
                status.textContent = error ? t('Connection interrupted') : '';
                status.hidden = !error;
            });
        } catch {
            status.textContent = t('Invalid API address');
        }
    }
}

if (!customElements.get('weewx-weather')) customElements.define('weewx-weather', WeatherWidget);
