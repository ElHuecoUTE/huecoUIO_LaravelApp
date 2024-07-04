<script setup lang="ts">
import { ProductTypeColumn } from '@/components/table/columns';
import DataTable from '@/components/table/DataTable.vue';
import { Button } from '@/components/ui/button';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DataTableDialogProductsTypes from '@/Pages/Dashboard/ProductsTypes/DataTableDialogProductsTypes.vue';
import DataTableDropdownProductsType from '@/Pages/Dashboard/ProductsTypes/DataTableDropdownProductsType.vue';
import { Head } from '@inertiajs/vue3';
import { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown } from 'lucide-vue-next';
import { h, ref, watch } from 'vue';

const props = defineProps<{
	data?: any;
	result?: ProductTypeColumn;
}>();

type CustomColumnDef =
	| ColumnDef<ProductTypeColumn>
	| {
			name: string;
	  };

const dataRef = ref(props.data);
const CLIENTS_COLUMNS: CustomColumnDef[] = [
	{
		id: 'tip_pro_nom',
		accessorKey: 'tip_pro_nom',
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
		cell: ({ row }) => h('div', { class: 'ml-4' }, [row.original.tip_pro_nom]),
		enableSorting: true,
		name: 'Nombre',
		filterFn: (row, _, filterValue) => {
			return row.original.tip_pro_nom.toLowerCase().includes(filterValue.toLowerCase());
		}
	},
	{
		id: 'actions',
		cell: ({ row }) => {
			return h('div', { class: 'relative float-end' }, [
				h(DataTableDropdownProductsType, {
					original: row.original,
					dataRef
				})
			]);
		},
		enableSorting: false,
		enableHiding: false
	}
];

const filters: string = 'tip_pro_nom';

watch(
	() => props.result,
	(value) => {
		if (value) {
			dataRef.value = [...dataRef.value, value];
		}
	}
);
</script>

<template>
	<Head title="Tipo de Productos" />

	<AuthenticatedLayout>
		<template #header>
			<h2 class="text-xl font-semibold leading-tight text-gray-800">Tipo de Productos</h2>
		</template>
		<div class="px-4 py-12">
			<div class="mx-auto flex max-w-7xl flex-col gap-y-4 sm:px-6 lg:px-8">
				<DataTable
					:data="dataRef || []"
					:columns="CLIENTS_COLUMNS as unknown as ColumnDef<any>[]"
					:filters="filters || ''"
					placeholder="Buscar tipo de producto por nombre"
				>
					<template #top>
						<DataTableDialogProductsTypes />
					</template>
				</DataTable>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
