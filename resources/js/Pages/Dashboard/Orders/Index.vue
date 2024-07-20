<script setup lang="ts">
import { OrderColumn } from '@/components/table/columns';
import DataTable from '@/components/table/DataTable.vue';
import { Button } from '@/components/ui/button';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DataTableDialogOrders from '@/Pages/Dashboard/Orders/DataTableDialogOrders.vue';
import DataTableDropdownOrders from '@/Pages/Dashboard/Orders/DataTableDropdownOrders.vue';
import { Head } from '@inertiajs/vue3';
import { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown } from 'lucide-vue-next';
import { h, ref, watch } from 'vue';

const props = defineProps<{
	orders?: any;
	result?: any;
	updateState?: any;
}>();

type CustomColumnDef =
	| ColumnDef<OrderColumn>
	| {
			name: string;
	  };

const dataRef = ref(props.orders || []);
const CLIENTS_COLUMNS: CustomColumnDef[] = [
	{
		id: 'ped_id',
		accessorKey: 'ped_id',
		header: ({ column }) => {
			return h(
				Button,
				{
					variant: 'ghost',
					onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
				},
				() => ['ID', h(ArrowUpDown, { class: 'w-4 h-4 ml-2' })]
			);
		},
		cell: ({ row }) => h('div', { class: 'ml-4' }, [row.original.ped_id]),
		enableSorting: true,
		name: 'ID'
	},
	{
		accessorKey: 'cli_nom',
		header: ({ column }) => {
			return h(
				Button,
				{
					variant: 'ghost',
					onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
				},
				() => ['Cliente', h(ArrowUpDown, { class: 'w-4 h-4 ml-2' })]
			);
		},
		cell: ({ row }) => h('div', { class: 'ml-4' }, [row.original.cliente.cli_nom]),
    filterFn: (row, _, filterValue) => {
      return row.original.cliente.cli_nom.toLowerCase().includes(filterValue.toLowerCase());
    },
		enableSorting: true,
		name: 'Cliente'
	},
	{
		accessorKey: 'ped_tot',
		header: ({ column }) => {
			return h(
				Button,
				{
					variant: 'ghost',
					onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
				},
				() => ['Total', h(ArrowUpDown, { class: 'w-4 h-4 ml-2' })]
			);
		},
		cell: ({ row }) => h('div', { class: 'ml-4' }, ['$' + row.original.ped_tot]),
		enableSorting: true,
		name: 'Total'
	},
	{
		accessorKey: 'estado_pedido.est_ped_nom',
		header: ({ column }) => {
			return h('div', ['Estado']);
		},
		cell: ({ row }) => h('div', { class: 'capitalize' }, [row.original.estado_pedido.est_ped_nom]),
		enableSorting: false,
    name: 'Estado'
	},
	{
		accessorKey: 'ped_fec',
		header: ({ column }) => {
			return h(
				Button,
				{
					variant: 'ghost',
					onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
				},
				() => ['Fecha', h(ArrowUpDown, { class: 'w-4 h-4 ml-2' })]
			);
		},
		cell: ({ row }) => h('div', { class: 'ml-4' }, [row.original.ped_fec]),
		enableSorting: true,
		name: 'Fecha'
	},
	{
		id: 'actions',
		cell: ({ row }) => {
			return h('div', { class: 'relative float-end' }, [
				h(DataTableDropdownOrders, {
					original: row.original,
					dataRef
				})
			]);
		},
		enableSorting: false,
		enableHiding: false
	}
];

const filters: string = 'cli_nom';

watch(
	() => props.result,
	(value) => {
		if (value) {
			dataRef.value = [...dataRef.value, value];
		}
	}
);

watch(
	() => props.updateState,
	(value) => {
		if (value) {
			const index = dataRef.value.findIndex((order: any) => order.ped_id === value.id);
			if (index < 0) {
				return;
			}

			dataRef.value[index].fk_est_ped_id = value.state;
			dataRef.value[index].estado_pedido.est_ped_id = value.state;
			dataRef.value[index].estado_pedido.est_ped_nom = value.stateName;
		}
	}
);
</script>

<template>
	<Head title="Pedidos" />

	<AuthenticatedLayout>
		<template #header>
			<h2 class="text-xl font-semibold leading-tight text-gray-800">Pedidos</h2>
		</template>

		<div class="px-4 py-12">
			<div class="mx-auto flex max-w-7xl flex-col gap-y-4 sm:px-6 lg:px-8">
				<DataTable
					:data="dataRef || []"
					:columns="CLIENTS_COLUMNS as unknown as ColumnDef<any>[]"
					:filters="filters || ''"
					placeholder="Buscar pedidos por nombre"
				>
					<template #top>
						<DataTableDialogOrders />
					</template>
				</DataTable>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
