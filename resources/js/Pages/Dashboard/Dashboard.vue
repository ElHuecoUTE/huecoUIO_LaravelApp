<script setup lang="ts">
import InfoCard from '@/components/InfoCard.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
	inventory_count?: any;
	last_30_days_entries?: any;
	last_30_days_exits?: any;
}>();

const inventoryCount = ref(props.inventory_count);
const last30DaysEntries = ref(props.last_30_days_entries);
const last30DaysExits = ref(props.last_30_days_exits);
</script>

<template>
	<Head title="Dashboard" />

	<AuthenticatedLayout>
		<template #header>
			<h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>
		</template>

		<div class="py-12">
			<div class="mx-auto flex max-w-7xl flex-col gap-y-4 sm:px-6 lg:px-8">
				<div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
					<div class="p-6 text-gray-900">
            <h3 class="text-lg font-semibold leading-tight">Bienvenido de vuelta, {{ $page.props.auth.user.name }}</h3>
            <p class="text-sm text-gray-600">Este es tu panel de control, aqui podras ver un resumen de todo inventario.</p>
          </div>
				</div>
				<div class="grid grid-cols-3 gap-4">
					<InfoCard
						title="Productos Totales"
						subtitle="Numero de productos creados"
						:mainValue="parseInt(inventoryCount)"
					/>
					<InfoCard
						title="Entradas"
						subtitle="En los ultimos 30 dias"
						:mainValue="parseInt(last30DaysEntries)"
					/>
					<InfoCard
						title="Salidas"
						subtitle="En los ultimos 30 dias"
						:mainValue="parseInt(last30DaysExits)"
					/>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
