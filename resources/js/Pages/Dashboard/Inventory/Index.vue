<script setup lang="ts">
import { InventoryColumn } from '@/components/table/columns';
import DataTable from '@/components/table/DataTable.vue';
import { Button } from '@/components/ui/button';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DataTableDropdownInventory from '@/Pages/Dashboard/Inventory/DataTableDropdownInventory.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown } from 'lucide-vue-next';
import { h, ref } from 'vue';

const props = defineProps<{
	inventory?: any;
}>();

type CustomColumnDef =
	| ColumnDef<InventoryColumn>
	| {
			name: string;
	  };

const dataRef = ref(props.inventory);
const CLIENTS_COLUMNS: CustomColumnDef[] = [
	{
		id: 'producto.pro_nom',
		accessorKey: 'producto.pro_nom',
		header: ({ column }) => {
			return h(
				Button,
				{
					variant: 'ghost',
					onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
				},
				() => ['Nombre', h(ArrowUpDown, { class: 'w-4 h-4 ml-2' })]
			);
		},
		cell: ({ row }) => h('div', { class: 'ml-4' }, [row.original.producto.pro_nom]),
		enableSorting: true,
		name: 'Nombre',
		filterFn: (row, _, filterValue) => {
			return row.original.producto.pro_nom.toLowerCase().includes(filterValue.toLowerCase());
		}
	},
	{
		accessorKey: 'producto.tipo_producto.tip_pro_nom',
		header: ({ column }) => {
			return h(
				Button,
				{
					variant: 'ghost',
					onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
				},
				() => ['Tipo', h(ArrowUpDown, { class: 'w-4 h-4 ml-2' })]
			);
		},
		cell: ({ row }) =>
			h('div', { class: 'ml-4' }, [row.original.producto.tipo_producto?.tip_pro_nom]),
		name: 'Tipo'
	},
	{
		accessorKey: 'inv_stock',
		header: ({ column }) => {
			return h(
				Button,
				{
					variant: 'ghost',
					onClick: () => {
						column.toggleSorting(column.getIsSorted() === 'asc');
					}
				},
				() => ['Stock', h(ArrowUpDown, { class: 'w-4 h-4 ml-2' })]
			);
		},
		cell: ({ row }) => h('div', { class: 'ml-4' }, [row.original.inv_stock]),
		name: 'Stock'
	},
	{
		id: 'actions',
		cell: ({ row }) => {
			return h('div', { class: 'relative float-end' }, [
				h(DataTableDropdownInventory, {
					original: row.original,
					dataRef,
					postForm
				})
			]);
		},
		enableSorting: false,
		enableHiding: false
	}
];

const postForm = useForm({
	id: -1,
	stock: 0
});
const filters: string = 'producto.pro_nom';
</script>

<template>
	<Head title="Inventario" />

	<AuthenticatedLayout>
		<template #header>
			<h2 class="text-xl font-semibold leading-tight text-gray-800">Inventario</h2>
		</template>
		<div class="px-4 py-12">
			<div class="mx-auto flex max-w-7xl flex-col gap-y-4 sm:px-6 lg:px-8">
				<DataTable
					:data="dataRef || []"
					:columns="CLIENTS_COLUMNS as unknown as ColumnDef<any>[]"
					:filters="filters || ''"
					placeholder="Buscar productos por nombre"
				>
				</DataTable>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
