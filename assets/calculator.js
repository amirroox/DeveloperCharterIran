let selectedSkill = { level: 'middle', min: 300000, max: 600000 };

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    setupTabNavigation();
    setupSkillButtons();
    setupEventListeners();
    document.querySelector('[data-level="middle"]').click();
});

// Tab Navigation
function setupTabNavigation() {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tabName = this.dataset.tab;
            
            // Remove active from all
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
            
            // Add active to clicked
            this.classList.add('active');
            document.getElementById(tabName).classList.add('active');
        });
    });
}

// Setup Skill Buttons
function setupSkillButtons() {
    document.querySelectorAll('.skill-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.skill-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            selectedSkill = {
                level: this.dataset.level,
                min: parseInt(this.dataset.min),
                max: parseInt(this.dataset.max)
            };
            calculate();
        });
    });
}

// Setup Event Listeners
function setupEventListeners() {
    document.getElementById('dollarPrice').addEventListener('change', calculate);
    document.getElementById('hours').addEventListener('change', calculate);
    document.getElementById('hourlyRate').addEventListener('change', calculate);
    document.getElementById('teamSize').addEventListener('change', calculate);
    document.getElementById('deadline').addEventListener('change', calculate);
    document.getElementById('support').addEventListener('change', calculate);
    
    document.querySelectorAll('.tech, .complexity, .factor-plus, .factor-minus').forEach(cb => {
        cb.addEventListener('change', calculate);
    });
}

// Main Calculation Function
function calculate() {
    const dollarPrice = parseInt(document.getElementById('dollarPrice').value) || 120000;
    const hours = parseFloat(document.getElementById('hours').value) || 0;
    const manualRate = parseInt(document.getElementById('hourlyRate').value) || 0;
    const teamSize = parseInt(document.getElementById('teamSize').value) || 1;
    const deadlineMultiplier = parseFloat(document.getElementById('deadline').value) || 1.0;
    const supportMultiplier = parseFloat(document.getElementById('support').value) || 1.0;

    // Base Price
    let hourlyRate;
    if (manualRate > 0) {
        hourlyRate = manualRate;
    } else {
        hourlyRate = selectedSkill.min + (selectedSkill.max - selectedSkill.min) / 2;
    }

    let basePrice = hourlyRate * hours;

    // Calculate Multipliers
    let techMultiplier = 1;
    let complexityMultiplier = 1;
    let factorMultiplier = 1;

    // Tech multiplier
    document.querySelectorAll('.tech:checked').forEach(cb => {
        techMultiplier *= parseFloat(cb.dataset.multiplier);
    });

    // Complexity multiplier
    document.querySelectorAll('.complexity:checked').forEach(cb => {
        complexityMultiplier *= parseFloat(cb.dataset.multiplier);
    });

    // Factor multiplier (plus and minus)
    let plusFactor = 1;
    let minusFactor = 1;

    document.querySelectorAll('.factor-plus:checked').forEach(cb => {
        plusFactor *= parseFloat(cb.dataset.multiplier);
    });

    document.querySelectorAll('.factor-minus:checked').forEach(cb => {
        minusFactor *= parseFloat(cb.dataset.multiplier);
    });

    factorMultiplier = plusFactor * minusFactor * deadlineMultiplier * supportMultiplier;

    let teamAdjustment = 1;
    if (teamSize > 1) {
        teamAdjustment = Math.max(0.7, 1 - (teamSize - 1) * 0.08);
    }

    // Final Price
    let finalPrice = basePrice * techMultiplier * complexityMultiplier * factorMultiplier * teamAdjustment;
    let finalPriceDollar = finalPrice / dollarPrice;

    // Display Results
    displayResults(basePrice, techMultiplier, complexityMultiplier, factorMultiplier, finalPrice, finalPriceDollar, dollarPrice, hours, hourlyRate);
}

// Display Results
function displayResults(base, tech, complexity, factor, final, finalDollar, dollar, hours, rate) {
    document.getElementById('basePrice').textContent = formatNumber(Math.round(base));
    document.getElementById('basePriceDesc').textContent = `${hours} ساعت × ${formatNumber(Math.round(rate))} تومان`;
    
    document.getElementById('techMult').textContent = tech.toFixed(2) + 'x';
    document.getElementById('complexityMult').textContent = complexity.toFixed(2) + 'x';
    document.getElementById('factorMult').textContent = factor.toFixed(2) + 'x';
    
    document.getElementById('finalPrice').textContent = formatNumber(Math.round(final));
    document.getElementById('finalPriceDollar').textContent = formatNumber(Math.round(finalDollar)) + ' $';

    // Breakdown
    updateBreakdown(base, tech, complexity, factor, final, hours, rate);
}

// Update Breakdown
function updateBreakdown(base, tech, complexity, factor, final, hours, rate) {
    const breakdown = document.getElementById('breakdown');
    
    let techPercent = ((tech - 1) * 100).toFixed(0);
    let complexPercent = ((complexity - 1) * 100).toFixed(0);
    let factorPercent = ((factor - 1) * 100).toFixed(0);
    let increase = ((final - base) / base * 100).toFixed(0);


    techPercent = techPercent.replace("-", 'منفی ');
    complexPercent = complexPercent.replace("-", 'منفی ');
    factorPercent = factorPercent.replace("-", 'منفی ');
    increase = increase.replace("-", 'منفی ');

    breakdown.innerHTML = `
        <div class="breakdown-item">
            <span>ساعت × نرخ:</span>
            <strong>${formatNumber(Math.round(base))} ت</strong>
        </div>
        <div class="breakdown-item">
            <span>تکنولوژی - ${techPercent} درصد:</span>
            <strong>×${tech.toFixed(2)}</strong>
        </div>
        <div class="breakdown-item">
            <span>پیچیدگی - ${complexPercent} درصد:</span>
            <strong>×${complexity.toFixed(2)}</strong>
        </div>
        <div class="breakdown-item">
            <span>عوامل اضافی - (${factorPercent}) درصد:</span>
            <strong>×${factor.toFixed(2)}</strong>
        </div>
        <div class="breakdown-item">
            <span>افزایش کل:</span>
            <strong>${increase} درصد</strong>
        </div>
        <div class="breakdown-item">
            <span>قیمت نهایی:</span>
            <strong style="color: #764ba2; font-size: 1.1em;">${formatNumber(Math.round(final))} ت</strong>
        </div>
    `;
}

// Format Number with Commas
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

document.getElementById("updateBtnDoller").addEventListener("click", function() {
    fetch("../calculator/update_rate.php")
        .then(res => res.text())
        .then(data => {
            if (data.includes("error:too_soon")) {
            } else if (data.includes("error")) {
            } else {
                document.getElementById("dollarPrice").value = parseInt(data.replace(/,/g, "")) / 10;
            }
        });
});
