const API_BASE = './api';

// Tab switching functionality
function switchTab(tabName) {
    document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    event.target.classList.add('active');
    document.getElementById(tabName + 'Tab').classList.add('active');
}

// Sign form submission handler
document.getElementById('signForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const button = document.getElementById('signButton');
    const message = document.getElementById('signMessage');

    button.disabled = true;
    button.textContent = 'در حال ارسال...';
    message.innerHTML = '';

    const data = {
        full_name: document.getElementById('fullName').value.trim(),
        email: document.getElementById('email').value.trim(),
        job_title: document.getElementById('jobTitle').value.trim(),
        company: document.getElementById('company').value.trim(),
        experience_years: document.getElementById('experience').value,
        city: document.getElementById('city').value.trim()
    };
    
    try {
        const response = await fetch(`${API_BASE}/sign.php`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            message.innerHTML = '<div class="alert alert-success">✅ ' + result.message + '<br>صفحه در حال بارگذاری مجدد...</div>';
            document.getElementById('signForm').reset();
            setTimeout(() => location.reload(), 2000);
        } else {
            message.innerHTML = '<div class="alert alert-error">❌ ' + result.message + '</div>';
        }
    } catch (error) {
        console.error('Sign form error:', error);
        message.innerHTML = '<div class="alert alert-error">❌ خطا در ارتباط با سرور. لطفا دوباره تلاش کنید.</div>';
    } finally {
        button.disabled = false;
        button.textContent = 'امضای منشور';
    }
});

// Report form submission handler
document.getElementById('reportForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const button = document.getElementById('reportButton');
    const message = document.getElementById('reportMessage');

    button.disabled = true;
    button.textContent = 'در حال ارسال...';
    message.innerHTML = '';

    const data = {
    reporter_email: document.getElementById('reporterEmail').value.trim(),
    reporter_type: document.getElementById('reporterType').value,

    violator_type: document.getElementById('violatorType').value,
    violator_name: document.getElementById('violatorName').value.trim(),
    violator_contact: document.getElementById('violatorContact').value.trim(),

    project_description: document.getElementById('projectDesc').value.trim(),
    estimated_fair_price: document.getElementById('fairPrice').value,
    offered_price: document.getElementById('offeredPrice').value,

    violation_type: document.getElementById('violationType').value,
    description: document.getElementById('description').value.trim(),
    evidence_url: document.getElementById('evidenceUrl').value.trim()
};

    
    try {
        const response = await fetch(`${API_BASE}/report.php`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            message.innerHTML = '<div class="alert alert-success">✅ ' + result.message + '</div>';
            document.getElementById('reportForm').reset();
            message.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            message.innerHTML = '<div class="alert alert-error">❌ ' + result.message + '</div>';
        }
    } catch (error) {
        console.error('Report form error:', error);
        message.innerHTML = '<div class="alert alert-error">❌ خطا در ارتباط با سرور. لطفا دوباره تلاش کنید.</div>';
    } finally {
        button.disabled = false;
        button.textContent = 'ارسال گزارش';
    }
});