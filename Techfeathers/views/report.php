<?php include __DIR__ . '/../includes/header.php'; ?>

<?php
$recentExports = $recentExports ?? [];
$totalEggsThisMonth = $totalEggsThisMonth ?? 0;
$feedConversion = $feedConversion ?? 0;
$avgMortality = $avgMortality ?? 0;
$trendLabels = $trendLabels ?? [];
$trendValues = $trendValues ?? [];
$financialTrend = $financialTrend ?? ['labels' => [], 'income' => [], 'expenses' => []];
?>

<div class="card-panel" style="display: grid; grid-template-columns: 320px 1fr; gap: 18px;">
    <div>
        <h2>Report Configuration</h2>
        <p style="margin-top:4px; color: var(--muted);">Select a template, date range, and export format.</p>

        <div style="display: grid; gap: 10px; margin-top: 14px;">
            <form id="reportForm" method="post" action="reports.php?action=generate" style="display: none;">
                <input type="hidden" name="report_type" id="reportType" value="batch_performance">
                <input type="hidden" name="start_date" id="startDate" value="<?= date('Y-m-d', strtotime('-30 days')) ?>">
                <input type="hidden" name="end_date" id="endDate" value="<?= date('Y-m-d') ?>">
                <input type="hidden" name="format" id="reportFormat" value="json">
            </form>

            <label style="font-weight: 600; font-size: 0.9rem;">Report Template</label>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <button class="report-template-btn active" type="button" data-type="batch_performance" style="background:#2ecc71; color:#fff; border:1px solid #2ecc71; cursor: pointer; padding: 14px; border-radius: 10px; font-size: 1.05rem; transition: background 0.2s ease;">Batch Performance Analysis</button>
                <button class="report-template-btn" type="button" data-type="financial" style="background:#fff; color:#111; border:1px solid rgba(0,0,0,0.12); cursor: pointer; padding: 14px; border-radius: 10px; font-size: 1.05rem; transition: background 0.2s ease;">Financial Report</button>
                <button class="report-template-btn" type="button" data-type="feed_inventory" style="background:#fff; color:#111; border:1px solid rgba(0,0,0,0.12); cursor: pointer; padding: 14px; border-radius: 10px; font-size: 1.05rem; transition: background 0.2s ease;">Feed Inventory</button>
            </div>

            <label style="font-weight: 600; font-size: 0.9rem;">Date Range</label>
            <div style="display:flex; gap:10px;">
                <input type="date" id="dateStart" value="<?= date('Y-m-d', strtotime('-30 days')) ?>" style="flex:1; padding:10px; border-radius:10px; border:1px solid rgba(0,0,0,0.12);" />
                <input type="date" id="dateEnd" value="<?= date('Y-m-d') ?>" style="flex:1; padding:10px; border-radius:10px; border:1px solid rgba(0,0,0,0.12);" />
            </div>

            <label style="font-weight: 600; font-size: 0.9rem;">Export Format</label>
            <div style="display:flex; gap:10px;">
                <button class="export-format-btn active" type="button" data-format="pdf" style="background:#2ecc71; color:#fff; border:1px solid #2ecc71; cursor: pointer; padding: 14px; border-radius: 10px; font-size: 1.05rem; transition: background 0.2s ease;">PDF</button>
                <button class="export-format-btn" type="button" data-format="excel" style="background:#fff; color:#111; border:1px solid rgba(0,0,0,0.12); cursor: pointer; padding: 14px; border-radius: 10px; font-size: 1.05rem; transition: background 0.2s ease;">Excel</button>
                <button class="export-format-btn" type="button" data-format="json" style="background:#fff; color:#111; border:1px solid rgba(0,0,0,0.12); cursor: pointer; padding: 14px; border-radius: 10px; font-size: 1.05rem; transition: background 0.2s ease;">JSON</button>
            </div>

            <button class="" type="button" id="generateReportBtn" style="background:#2ecc71; color:#fff; border:1px solid #2ecc71; cursor: pointer; padding: 14px; border-radius: 10px; font-size: 1.05rem; transition: background 0.2s ease; margin-top: 8px;">Generate Report</button>
        </div>

        <div style="margin-top: 22px;">
            <h3 style="margin:0;">Recent Exports</h3>
            <div style="margin-top: 10px; display: grid; gap: 10px;">
                <?php foreach ($recentExports as $export): ?>
                    <div style="display:flex; justify-content:space-between; align-items:center; padding: 10px 12px; border:1px solid rgba(0,0,0,0.1); border-radius: 12px;">
                        <div>
                            <div style="font-weight:600;"><?= htmlspecialchars($export['name']) ?></div>
                            <div style="font-size:0.85rem; color: var(--muted);"><?= htmlspecialchars($export['time']) ?></div>
                        </div>
                        <span style="font-size:1.2rem;">⬇️</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div style="display: grid; gap: 16px;">
        <h2>Batch Performance Analysis</h2>
        <p style="margin-top:4px; color: var(--muted);">Lorem ipsum dolor sit amet</p>

        <div class="stats" style="grid-template-columns: repeat(3, minmax(180px, 1fr));">
            <div class="stat">
                <h3>Total Production</h3>
                <p><?= number_format($totalEggsThisMonth) ?> Eggs this month</p>
            </div>
            <div class="stat">
                <h3>Feed Conversion</h3>
                <p><?= number_format($feedConversion, 2) ?> kg/egg</p>
            </div>
            <div class="stat">
                <h3>Avg. Mortality</h3>
                <p><?= number_format($avgMortality, 1) ?>%</p>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.9); padding: 14px 16px; border-radius: 18px; border: 1px solid rgba(0,0,0,0.08);">
            <h3 style="margin:0 0 10px;">Production vs. Financials Trend</h3>
            <div style="max-width:520px; height: 280px;">
                <canvas id="reportTrendChart" style="width:100%; height:100%;"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    const reportLabels = <?= json_encode($trendLabels, JSON_HEX_TAG) ?>;
    const eggData = <?= json_encode($trendValues, JSON_HEX_TAG) ?>;
    const incomeData = <?= json_encode($financialTrend['income'], JSON_HEX_TAG) ?>;
    const expenseData = <?= json_encode($financialTrend['expenses'], JSON_HEX_TAG) ?>;

    new Chart(document.getElementById('reportTrendChart'), {
        type: 'line',
        data: {
            labels: reportLabels,
            datasets: [{
                label: 'Egg Production',
                data: eggData,
                borderColor: 'rgba(46, 204, 113, 0.9)',
                backgroundColor: 'rgba(46, 204, 113, 0.15)',
                tension: 0.3,
                fill: true,
                yAxisID: 'y',
                pointRadius: 4,
                pointHoverRadius: 6,
            }, {
                label: 'Income ($)',
                data: incomeData,
                borderColor: 'rgba(52, 152, 219, 0.9)',
                backgroundColor: 'rgba(52, 152, 219, 0.15)',
                tension: 0.3,
                fill: true,
                yAxisID: 'y1',
                pointRadius: 4,
                pointHoverRadius: 6,
            }, {
                label: 'Expenses ($)',
                data: expenseData,
                borderColor: 'rgba(231, 76, 60, 0.9)',
                backgroundColor: 'rgba(231, 76, 60, 0.15)',
                tension: 0.3,
                fill: true,
                yAxisID: 'y1',
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Eggs'
                    },
                    ticks: {
                        precision: 0
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Amount ($)'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            },
            plugins: {
                legend: { display: true }
            }
        }
    });

    // Report generation functionality
    document.addEventListener('DOMContentLoaded', function() {
        const reportForm = document.getElementById('reportForm');
        const generateBtn = document.getElementById('generateReportBtn');
        const dateStart = document.getElementById('dateStart');
        const dateEnd = document.getElementById('dateEnd');

        // Template selection
        document.querySelectorAll('.report-template-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Reset all buttons to inactive style
                document.querySelectorAll('.report-template-btn').forEach(b => {
                    b.classList.remove('active');
                    b.style.background = '#fff';
                    b.style.color = '#111';
                    b.style.border = '1px solid rgba(0,0,0,0.12)';
                });
                // Set this button to active style
                this.classList.add('active');
                this.style.background = '#2ecc71';
                this.style.color = '#fff';
                this.style.border = '1px solid #2ecc71';
                document.getElementById('reportType').value = this.dataset.type;
            });
        });

        // Format selection
        document.querySelectorAll('.export-format-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Reset all buttons to inactive style
                document.querySelectorAll('.export-format-btn').forEach(b => {
                    b.classList.remove('active');
                    b.style.background = '#fff';
                    b.style.color = '#111';
                    b.style.border = '1px solid rgba(0,0,0,0.12)';
                });
                // Set this button to active style
                this.classList.add('active');
                this.style.background = '#2ecc71';
                this.style.color = '#fff';
                this.style.border = '1px solid #2ecc71';
                document.getElementById('reportFormat').value = this.dataset.format;
            });
        });

        // Date change handlers
        dateStart.addEventListener('change', function() {
            document.getElementById('startDate').value = this.value;
        });

        dateEnd.addEventListener('change', function() {
            document.getElementById('endDate').value = this.value;
        });

        // Generate report
        generateBtn.addEventListener('click', function() {
            const formData = new FormData(reportForm);
            const reportType = document.getElementById('reportType').value;
            const reportFormat = document.getElementById('reportFormat').value;

            generateBtn.textContent = 'Generating...';
            generateBtn.disabled = true;

            fetch('reports.php?action=generate', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (reportFormat === 'json') {
                    return response.json();
                } else {
                    // For CSV and text files, get the text content
                    return response.text().then(text => ({
                        content: text,
                        contentType: response.headers.get('content-type'),
                        filename: response.headers.get('content-disposition')?.match(/filename="(.+)"/)?.[1] || `${reportType}_report_${new Date().toISOString().split('T')[0]}.${reportFormat === 'excel' ? 'csv' : 'txt'}`
                    }));
                }
            })
            .then(data => {
                if (reportFormat === 'json') {
                    // Display JSON data on the page
                    displayReportResults(data, reportType);
                } else {
                    // Download the file
                    const blob = new Blob([data.content], { type: data.contentType || 'text/plain' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = data.filename;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);

                    alert(`Report downloaded successfully!\n\nFile: ${data.filename}\nFormat: ${reportFormat.toUpperCase()}\n\n${reportFormat === 'excel' ? 'Open with Excel or any spreadsheet application.' : 'Save as PDF or print for PDF output.'}`);
                }
            })
            .catch(error => {
                console.error('Error generating report:', error);
                alert('Error generating report. Please try again.');
            })
            .finally(() => {
                generateBtn.textContent = 'Generate Report';
                generateBtn.disabled = false;
            });
        });
    });

    function displayReportResults(data, reportType) {
        // Remove any existing report results
        const existingResults = document.getElementById('reportResults');
        if (existingResults) {
            existingResults.remove();
        }

        // Create a new results section
        const resultsDiv = document.createElement('div');
        resultsDiv.id = 'reportResults';
        resultsDiv.style.cssText = 'background: rgba(255,255,255,0.9); padding: 20px; border-radius: 18px; border: 1px solid rgba(0,0,0,0.08); margin-top: 20px;';

        let html = `<h3 style="margin:0 0 15px 0; color: #2ecc71;">${getReportTitle(reportType)} - Generated Report</h3>`;

        if (data.error) {
            html += `<div style="color: #e74c3c; padding: 10px; background: rgba(231, 76, 60, 0.1); border-radius: 8px;">Error: ${data.error}</div>`;
        } else {
            html += formatReportData(data, reportType);
        }

        resultsDiv.innerHTML = html;

        // Insert after the chart
        const chartContainer = document.querySelector('.card-panel');
        chartContainer.appendChild(resultsDiv);

        // Scroll to results
        resultsDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function getReportTitle(type) {
        const titles = {
            'batch_performance': 'Batch Performance Analysis',
            'financial': 'Financial Report',
            'feed_inventory': 'Feed Inventory Report'
        };
        return titles[type] || 'Report';
    }

    function formatReportData(data, type) {
        let html = '';

        if (type === 'batch_performance') {
            html += '<h4>Batch Performance Data</h4>';
            if (data.batches && data.batches.length > 0) {
                html += '<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">';
                html += '<thead><tr style="background: #f8f9fa;"><th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Batch Code</th><th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Breed</th><th style="padding: 10px; border: 1px solid #ddd; text-align: right;">Total Eggs</th><th style="padding: 10px; border: 1px solid #ddd; text-align: right;">Avg Daily</th></tr></thead>';
                html += '<tbody>';
                data.batches.forEach(batch => {
                    html += `<tr>
                        <td style="padding: 10px; border: 1px solid #ddd;">${batch.batch_code}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">${batch.breed}</td>
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: right;">${batch.total_eggs}</td>
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: right;">${Math.round(batch.avg_daily)}</td>
                    </tr>`;
                });
                html += '</tbody></table>';
            }

            if (data.mortality && data.mortality.length > 0) {
                html += '<h4>Mortality Data</h4>';
                html += '<table style="width: 100%; border-collapse: collapse;">';
                html += '<thead><tr style="background: #f8f9fa;"><th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Batch Code</th><th style="padding: 10px; border: 1px solid #ddd; text-align: right;">Total Deaths</th></tr></thead>';
                html += '<tbody>';
                data.mortality.forEach(m => {
                    html += `<tr>
                        <td style="padding: 10px; border: 1px solid #ddd;">${m.batch_code}</td>
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: right;">${m.total_deaths}</td>
                    </tr>`;
                });
                html += '</tbody></table>';
            }
        } else if (type === 'financial') {
            html += '<h4>Financial Summary</h4>';
            html += `<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px;">
                <div style="background: rgba(46, 204, 113, 0.1); padding: 15px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 1.5rem; font-weight: bold; color: #2ecc71;">$${data.summary.total_income.toFixed(2)}</div>
                    <div style="color: #666;">Total Income</div>
                </div>
                <div style="background: rgba(231, 76, 60, 0.1); padding: 15px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 1.5rem; font-weight: bold; color: #e74c3c;">$${data.summary.total_expenses.toFixed(2)}</div>
                    <div style="color: #666;">Total Expenses</div>
                </div>
                <div style="background: rgba(52, 152, 219, 0.1); padding: 15px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 1.5rem; font-weight: bold; color: #3498db;">$${(data.summary.total_income - data.summary.total_expenses).toFixed(2)}</div>
                    <div style="color: #666;">Net Profit</div>
                </div>
            </div>`;

            if (data.transactions && data.transactions.length > 0) {
                html += '<h4>Transaction Details</h4>';
                html += '<table style="width: 100%; border-collapse: collapse;">';
                html += '<thead><tr style="background: #f8f9fa;"><th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Date</th><th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Type</th><th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Description</th><th style="padding: 10px; border: 1px solid #ddd; text-align: right;">Amount</th></tr></thead>';
                html += '<tbody>';
                data.transactions.forEach(t => {
                    const typeColor = t.type === 'sale' ? '#2ecc71' : '#e74c3c';
                    html += `<tr>
                        <td style="padding: 10px; border: 1px solid #ddd;">${t.incurred_at}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;"><span style="background: ${typeColor}; color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.8rem;">${t.type}</span></td>
                        <td style="padding: 10px; border: 1px solid #ddd;">${t.description}</td>
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: right; font-weight: bold;">$${t.amount.toFixed(2)}</td>
                    </tr>`;
                });
                html += '</tbody></table>';
            }
        } else if (type === 'feed_inventory') {
            html += '<h4>Feed Inventory</h4>';
            if (data.inventory && data.inventory.length > 0) {
                html += '<table style="width: 100%; border-collapse: collapse;">';
                html += '<thead><tr style="background: #f8f9fa;"><th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Feed Name</th><th style="padding: 10px; border: 1px solid #ddd; text-align: right;">Quantity (kg)</th><th style="padding: 10px; border: 1px solid #ddd; text-align: right;">Unit Cost</th><th style="padding: 10px; border: 1px solid #ddd; text-align: right;">Total Value</th><th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Last Updated</th></tr></thead>';
                html += '<tbody>';
                data.inventory.forEach(item => {
                    const totalValue = item.quantity_kg * item.unit_cost;
                    html += `<tr>
                        <td style="padding: 10px; border: 1px solid #ddd;">${item.name}</td>
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: right;">${item.quantity_kg}</td>
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: right;">$${item.unit_cost.toFixed(2)}</td>
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: right; font-weight: bold;">$${totalValue.toFixed(2)}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">${item.last_updated}</td>
                    </tr>`;
                });
                html += '</tbody></table>';
            } else {
                html += '<p>No feed inventory data available.</p>';
            }
        }

        if (data.period) {
            html += `<div style="margin-top: 20px; padding: 10px; background: #f8f9fa; border-radius: 8px; font-size: 0.9rem;">
                <strong>Report Period:</strong> ${data.period.start} to ${data.period.end}
            </div>`;
        }

        return html;
    }
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>