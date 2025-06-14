<div class="banner-wrapper">
  <div class="banner-content">
    <div class="banner-text">
      <h1>Global Currency Markets</h1>
      <div class="market-insights">
        <div class="insight-card">
          <span class="label">EUR/USD</span>
          <span class="rate" id="banner-eurusd-rate">1.08</span>
          <span class="change positive" id="banner-eurusd-change">+0.15%</span>
        </div>
        <div class="insight-card">
          <span class="label">GBP/USD</span>
          <span class="rate" id="banner-gbpusd-rate">1.27</span>
          <span class="change negative" id="banner-gbpusd-change">-0.05%</span>
        </div>
        <div class="insight-card">
          <span class="label">USD/JPY</span>
          <span class="rate" id="banner-usdjpy-rate">148.45</span>
          <span class="change positive" id="banner-usdjpy-change">+0.22%</span>
        </div>
      </div>
      <p class="market-summary">Market is showing mixed signals with EUR gaining strength against USD while GBP faces pressure. Stay updated with real-time rates and trends.</p>
    </div>
    <div class="banner-actions">
      <button class="primary-btn">View All Rates</button>
      <button class="secondary-btn">Set Rate Alert</button>
    </div>
  </div>
</div>
<main>
    <div id="general-section">
      <section class="converter-section">
        <h1>EUR/USD Currency Converter</h1>
        <form id="converter-form">
          <input type="number" id="amount" placeholder="Amount" required>
          <select id="from-currency" class="currency-select">
            <option value="EUR">EUR - Euro</option>
            <option value="USD">USD - US Dollar</option>
          </select>
          <span>to</span>
          <select id="to-currency" class="currency-select">
            <option value="USD">USD - US Dollar</option>
            <option value="EUR">EUR - Euro</option>
          </select>
          <button type="submit">Convert</button>
        </form>
        <div id="conversion-result"></div>
      </section>
      <section class="rates-section">
        <h2>Live EUR/USD Exchange Rate</h2>
        <table id="rates-table">
          <thead>
            <tr><th>Currency Pair</th><th>Rate</th><th>Change</th><th>Trend</th><th></th></tr>
          </thead>
          <tbody>
            <tr>
              <td>EUR/USD</td>
              <td id="eurusd-rate">-</td>
              <td id="eurusd-change">-</td>
              <td id="eurusd-trend">-</td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </section>
      <section class="app-promo-section">
        <div class="app-info">
          <h3>Manage your currencies on the go with the Xe app</h3>
          <button>Download app</button>
        </div>
        <div class="app-image">
          <!-- Placeholder for app image -->
        </div>
      </section>
      <section class="tools-section">
        <h2>Xe currency tools</h2>
        <div class="tools-grid">
          <div class="tool-card">International transfers</div>
          <div class="tool-card">Rate alerts</div>
          <div class="tool-card">Historical currency rates</div>
          <div class="tool-card">IBAN calculator</div>
        </div>
      </section>
      <section class="api-section">
        <h2>Xe currency data API</h2>
        <div class="api-info">
          <p>The world's most trusted source for currency data</p>
          <pre>{
  "from": "EUR",
  "to": "USD",
  "rate": 1.08
}</pre>
          <button class="primary-btn">Learn more</button>
        </div>
      </section>
      <section class="testimonials-section">
        <h2>Xe is trusted by millions around the globe</h2>
        <div class="testimonials-grid">
          <div class="testimonial-card">Testimonial 1</div>
          <div class="testimonial-card">Testimonial 2</div>
          <div class="testimonial-card">Testimonial 3</div>
        </div>
      </section>
      <section class="destinations-section">
        <h2>Send money destinations</h2>
        <div class="destinations-grid">
          <!-- Destinations will be loaded here -->
        </div>
      </section>
    </div>
    <div id="trends-section" style="display:none;">
      <section class="trends-section">
        <h1>Currency Trends</h1>
        <div id="trends-content">
          <!-- Trends data or chart will be loaded here -->
          <p>Trends and historical data coming soon...</p>
        </div>
      </section>
    </div>
  </main>