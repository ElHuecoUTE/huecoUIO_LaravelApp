<script setup lang="ts">
import { SalesColumn } from '@/components/table/columns';
import DataTable from '@/components/table/DataTable.vue';
import { Button } from '@/components/ui/button';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DataTableDialogSales from '@/Pages/Dashboard/Sales/DataTableDialogSales.vue';
import { Head } from '@inertiajs/vue3';
import { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown } from 'lucide-vue-next';
import { h, ref, watch } from 'vue';

const props = defineProps<{
	data?: any;
	result?: SalesColumn;
}>();

type CustomColumnDef =
	| ColumnDef<SalesColumn>
	| {
			name: string;
	  };

const dataRef = ref(props.data);
const CLIENTS_COLUMNS: CustomColumnDef[] = [
	{
		id: 'ven_id',
		accessorKey: 'ven_id',
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
		cell: ({ row }) => h('div', { class: 'ml-4' }, [row.original.ven_id]),
		enableSorting: true,
		name: 'ID',
    filterFn: (row, _, filterValue) => {
      return (
        row.original.ven_id.toString().toLowerCase().includes(filterValue.toLowerCase()) ||
        row.original.fk_ped_id.toString().toLowerCase().includes(filterValue.toLowerCase()) ||
        new Date(row.original.created_at).toLocaleDateString().toLowerCase().includes(filterValue.toLowerCase())
      );
    },
	},
  {
    id: 'fk_ped_id',
    accessorKey: 'fk_ped_id',
    header: ({ column }) => {
      return h(
        Button,
        {
          variant: 'ghost',
          onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
        },
        () => ['Pedido', h(ArrowUpDown, { class: 'w-4 h-4 ml-2' })]
      );
    },
    cell: ({ row }) => h('div', { class: 'ml-4' }, [row.original.fk_ped_id]),
    enableSorting: true,
    name: 'Pedido',
  },
  {
    id: 'ven_tot',
    accessorKey: 'ven_tot',
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
    cell: ({ row }) => h('div', { class: 'ml-4' }, ['$' + row.original.ven_tot]),
    enableSorting: true,
    name: 'Total',
  },
  {
    id: 'created_at',
    accessorKey: 'created_at',
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
    cell: ({ row }) => h('div', { class: 'ml-4' }, [new Date(row.original.created_at).toLocaleDateString()]),
    enableSorting: true,
    name: 'Fecha',
  },
];

const filters: string = 'ven_id';

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
	<Head title="Ventas" />

	<AuthenticatedLayout>
		<template #header>
			<h2 class="text-xl font-semibold leading-tight text-gray-800">Ventas</h2>
		</template>
		<div class="px-4 py-12">
			<div class="mx-auto flex max-w-7xl flex-col gap-y-4 sm:px-6 lg:px-8">
				<DataTable
					:data="dataRef || []"
					:columns="CLIENTS_COLUMNS as unknown as ColumnDef<any>[]"
					:filters="filters || ''"
					placeholder="Buscar ventas..."
				>
					<template #top>
						<DataTableDialogSales />
					</template>
				</DataTable>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
