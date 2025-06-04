<div class="container mx-auto p-6 space-y-8">

    <h1 class="text-3xl font-bold mb-8 text-center">Dashboard de Serviços</h1>

    {{-- Cards de totais --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
        <div class="stats bg-base-100 border border-base-300 rounded-lg shadow p-4">
            <div class="stat-title text-lg font-semibold">Total Ganho (€)</div>
            <div class="stat-value text-3xl text-green-600">€{{ number_format($totalGanho, 2, ',', '.') }}</div>
            <div class="stat-actions">
                <button wire:click="loadTotals" class="btn btn-xs btn-success">Atualizar</button>
            </div>
        </div>
        <div class="stats bg-base-100 border border-base-300 rounded-lg shadow p-4">
            <div class="stat-title text-lg font-semibold">Total Comissão (€)</div>
            <div class="stat-value text-3xl text-red-600">€{{ number_format($totalComissao, 2, ',', '.') }}</div>
            <div class="stat-actions">
                <button wire:click="loadTotals" class="btn btn-xs btn-error">Atualizar</button>
            </div>
        </div>
    </div>

    {{-- Gráfico de linha - Serviços por mês --}}
    <section class="bg-white p-6 rounded-lg shadow max-w-4xl mx-auto">
        <h2 class="text-xl font-semibold mb-4 text-center">Total de Serviços por Mês (Últimos 12 meses)</h2>
        <x-chart wire:model="chartServicosMes" />
    </section>

    {{-- Outros gráficos em grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        {{-- Receita por Serviço --}}
        <section class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4 text-center">Receita por Serviço</h2>
            <x-chart wire:model="chartReceita" />
        </section>

        {{-- Serviços por Staff --}}
        <section class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4 text-center">Serviços realizados por Staff</h2>
            <x-chart wire:model="chartStaff" />
        </section>

        {{-- Serviços por Espaço --}}
        <section class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4 text-center">Serviços por Espaço</h2>
            <x-chart wire:model="chartSpace" />
        </section>

    </div>
</div>
