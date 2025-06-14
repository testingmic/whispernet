<div class="rates-banner">
  <div class="rates-banner-content">
    <div class="quick-converter">
      <h2>Quick Currency Converter</h2>
      <form id="quick-converter-form" class="quick-converter-form">
        <div class="converter-inputs">
          <input type="number" id="quick-amount" placeholder="Amount" required>
          <select id="quick-from-currency" class="currency-select">
            <option value="EUR">EUR - Euro</option>
            <option value="USD">USD - US Dollar</option>
            <option value="GBP">GBP - British Pound</option>
            <option value="JPY">JPY - Japanese Yen</option>
            <option value="AUD">AUD - Australian Dollar</option>
            <option value="CAD">CAD - Canadian Dollar</option>
            <option value="CHF">CHF - Swiss Franc</option>
            <option value="CNY">CNY - Chinese Yuan</option>
          </select>
          <span class="converter-arrow">→</span>
          <select id="quick-to-currency" class="currency-select">
            <option value="USD">USD - US Dollar</option>
            <option value="EUR">EUR - Euro</option>
            <option value="GBP">GBP - British Pound</option>
            <option value="JPY">JPY - Japanese Yen</option>
            <option value="AUD">AUD - Australian Dollar</option>
            <option value="CAD">CAD - Canadian Dollar</option>
            <option value="CHF">CHF - Swiss Franc</option>
            <option value="CNY">CNY - Chinese Yuan</option>
          </select>
        </div>
        <button type="submit" class="primary-btn">Convert</button>
      </form>
      <div id="quick-conversion-result" class="conversion-result"></div>
    </div>
  </div>
</div>

<main>
  <div class="rates-container">
    <div class="rates-header">
      <h1>Live Exchange Rates</h1>
      <div class="rates-filter">
        <input type="text" id="currency-search" placeholder="Search currencies..." class="search-input">
        <select id="base-currency" class="currency-select">
          <option value="USD">USD - US Dollar</option>
          <option value="EUR">EUR - Euro</option>
          <option value="GBP">GBP - British Pound</option>
          <option value="JPY">JPY - Japanese Yen</option>
          <option value="AUD">AUD - Australian Dollar</option>
          <option value="CAD">CAD - Canadian Dollar</option>
          <option value="CHF">CHF - Swiss Franc</option>
          <option value="CNY">CNY - Chinese Yuan</option>
        </select>
      </div>
    </div>

    <div class="rates-table-container">
      <table id="rates-table" class="rates-table">
        <thead>
          <tr>
            <th>Currency</th>
            <th>Code</th>
            <th>Rate</th>
            <th>Change (24h)</th>
            <th>Chart</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Rates will be loaded dynamically -->
        </tbody>
      </table>
    </div>

    <div class="rates-info">
      <div class="info-card">
        <h3>About Exchange Rates</h3>
        <p>Exchange rates are updated every minute and are based on data from multiple sources. Rates shown are mid-market rates, which are the average of buy and sell rates.</p>
      </div>
      <div class="info-card">
        <h3>Rate Alerts</h3>
        <p>Set up rate alerts to be notified when your desired exchange rate is reached. Perfect for planning international transfers.</p>
        <button class="secondary-btn">Set Rate Alert</button>
      </div>
    </div>
  </div>
</main>