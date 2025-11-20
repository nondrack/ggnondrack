<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DualCore Tech</title>
    
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/dark-theme.css">
    <link rel="stylesheet" href="css/style.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-card {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border: 1px solid #00eaff30;
            transition: all 0.3s ease;
        }
        .dashboard-card:hover {
            border-color: #00eaff;
            box-shadow: 0 0 15px #00eaff20;
        }
        .stat-icon {
            font-size: 3rem;
            color: #00eaff;
        }
        .chart-container {
            position: relative;
            height: 300px;
            margin: 20px 0;
        }
        .metric-value {
            font-size: 2.5rem;
            font-weight: bold;
            color: #00eaff;
        }
        .metric-label {
            font-size: 0.9rem;
            color: #aaa;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h1 class="text-light mb-0">
                        <i class="fas fa-chart-bar me-2" style="color: #00eaff;"></i>
                        Dashboard de Indicadores
                    </h1>
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <a href="index.php?param=produto/index" class="btn btn-success btn-sm">
                            <i class="fas fa-box me-1"></i> Cadastrar Produto
                        </a>
                        <a href="index.php?param=categoria/index" class="btn btn-warning btn-sm">
                            <i class="fas fa-tags me-1"></i> Cadastrar Categoria
                        </a>
                        <a href="index.php" class="btn btn-outline-neon btn-sm">
                            <i class="fas fa-home"></i> Voltar ao Site
                        </a>
                        <span class="text-muted d-none d-md-inline">Última atualização: <?= date('d/m/Y H:i') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Métricas Principais -->
        <div class="row mb-4">
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="card dashboard-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-box stat-icon mb-2"></i>
                        <div class="metric-value"><?= number_format($indicadores->total_produtos ?? 0) ?></div>
                        <div class="metric-label">Produtos Ativos</div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="card dashboard-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-users stat-icon mb-2"></i>
                        <div class="metric-value"><?= number_format($indicadores->total_usuarios ?? 0) ?></div>
                        <div class="metric-label">Usuários</div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="card dashboard-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-shopping-cart stat-icon mb-2"></i>
                        <div class="metric-value"><?= number_format($indicadores->total_vendas ?? 0) ?></div>
                        <div class="metric-label">Vendas Realizadas</div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                <div class="card dashboard-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-dollar-sign stat-icon mb-2"></i>
                        <div class="metric-value">R$ <?= number_format($indicadores->receita_total ?? 0, 2, ',', '.') ?></div>
                        <div class="metric-label">Receita Total</div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                <div class="card dashboard-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-exclamation-triangle stat-icon mb-2" style="color: #ff6b6b;"></i>
                        <div class="metric-value" style="color: #ff6b6b;"><?= number_format($indicadores->produtos_baixo_estoque ?? 0) ?></div>
                        <div class="metric-label">Baixo Estoque</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card dashboard-card">
                    <div class="card-header border-0">
                        <h5 class="text-light mb-0">
                            <i class="fas fa-chart-line me-2"></i>
                            Vendas por Mês
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="vendasChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card dashboard-card">
                    <div class="card-header border-0">
                        <h5 class="text-light mb-0">
                            <i class="fas fa-chart-pie me-2"></i>
                            Categorias Populares
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="categoriasChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabelas de Dados -->
        <div class="row mb-4">
            <div class="col-lg-6">
                <div class="card dashboard-card">
                    <div class="card-header border-0">
                        <h5 class="text-light mb-0">
                            <i class="fas fa-trophy me-2"></i>
                            Produtos Mais Vendidos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Vendidos</th>
                                        <th>Receita</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($produtosMaisVendidos)): ?>
                                        <?php foreach ($produtosMaisVendidos as $produto): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($produto->nome) ?></td>
                                                <td><span class="badge bg-success"><?= $produto->total_vendido ?></span></td>
                                                <td>R$ <?= number_format($produto->receita, 2, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Nenhum produto vendido ainda</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card dashboard-card">
                    <div class="card-header border-0">
                        <h5 class="text-light mb-0">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Produtos com Baixo Estoque
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Estoque</th>
                                        <th>Preço</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($produtosBaixoEstoque)): ?>
                                        <?php foreach ($produtosBaixoEstoque as $produto): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($produto->nome) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $produto->estoque < 5 ? 'danger' : 'warning' ?>">
                                                        <?= $produto->estoque ?>
                                                    </span>
                                                </td>
                                                <td>R$ <?= number_format($produto->preco, 2, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-success">Todos os produtos com estoque adequado</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vendas Recentes -->
        <div class="row">
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-header border-0">
                        <h5 class="text-light mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Vendas Recentes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Cliente</th>
                                        <th>Data</th>
                                        <th>Status</th>
                                        <th>Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($vendasRecentes)): ?>
                                        <?php foreach ($vendasRecentes as $venda): ?>
                                            <tr>
                                                <td>#<?= $venda->id ?></td>
                                                <td><?= htmlspecialchars($venda->cliente ?? 'Cliente não identificado') ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($venda->data_criacao)) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= 
                                                        $venda->status === 'paga' ? 'success' : 
                                                        ($venda->status === 'aguardando_pagamento' ? 'warning' : 'secondary') 
                                                    ?>">
                                                        <?= ucfirst($venda->status) ?>
                                                    </span>
                                                </td>
                                                <td>R$ <?= number_format($venda->valor_total, 2, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Nenhuma venda registrada ainda</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        // Dados dos gráficos
        const vendasData = <?= json_encode($vendasPorMes ?? []) ?>;
        const categoriasData = <?= json_encode($categoriasPopulares ?? []) ?>;

        // Gráfico de Vendas por Mês
        const ctxVendas = document.getElementById('vendasChart').getContext('2d');
        new Chart(ctxVendas, {
            type: 'line',
            data: {
                labels: vendasData.map(item => {
                    const [ano, mes] = item.mes.split('-');
                    return `${mes}/${ano}`;
                }).reverse(),
                datasets: [{
                    label: 'Vendas',
                    data: vendasData.map(item => item.total_vendas).reverse(),
                    borderColor: '#00eaff',
                    backgroundColor: 'rgba(0, 234, 255, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Receita (R$)',
                    data: vendasData.map(item => item.receita).reverse(),
                    borderColor: '#ff6b6b',
                    backgroundColor: 'rgba(255, 107, 107, 0.1)',
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#fff' }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#fff' },
                        grid: { color: '#333' }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        ticks: { color: '#fff' },
                        grid: { color: '#333' }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        ticks: { color: '#fff' },
                        grid: { drawOnChartArea: false }
                    }
                }
            }
        });

        // Gráfico de Categorias Populares
        const ctxCategorias = document.getElementById('categoriasChart').getContext('2d');
        new Chart(ctxCategorias, {
            type: 'doughnut',
            data: {
                labels: categoriasData.map(item => item.nome),
                datasets: [{
                    data: categoriasData.map(item => item.total_vendido),
                    backgroundColor: [
                        '#00eaff',
                        '#ff6b6b',
                        '#4ecdc4',
                        '#45b7d1',
                        '#96ceb4'
                    ],
                    borderWidth: 2,
                    borderColor: '#1a1a2e'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { 
                            color: '#fff',
                            padding: 20
                        }
                    }
                }
            }
        });

        // Auto-refresh da página a cada 5 minutos
        setTimeout(() => {
            location.reload();
        }, 300000);
    </script>
</body>
</html>