<script setup lang="ts">
import type { ClientColumn } from '@/components/table/columns';
import DataTable from '@/components/table/DataTable.vue';
import { Button } from '@/components/ui/button';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DataTableDialogCliente from '@/Pages/Dashboard/Clients/DataTableDialogClient.vue';
import DataTableDropdownClient from '@/Pages/Dashboard/Clients/DataTableDropdownClient.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown } from 'lucide-vue-next';
import { h, ref, watch } from 'vue';

const props = defineProps<{
	data?: any;
	result?: any;
}>();

type CustomColumnDef =
	| ColumnDef<ClientColumn>
	| {
			name: string;
	  };

const CLIENTS_COLUMNS: CustomColumnDef[] = [
	{
		accessorKey: 'cli_nom',
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
		cell: ({ row }) =>
			h('div', { class: 'ml-4' }, [row.original.cli_nom, ' ', row.original.cli_ape]),
		filterFn: (row, _, filterValue) => {
			return (
				row.original.cli_nom.toLowerCase().includes(filterValue.toLowerCase()) ||
				row.original.cli_ape.toLowerCase().includes(filterValue.toLowerCase()) ||
				row.original.cli_ema.toLowerCase().includes(filterValue.toLowerCase())
			);
		},
		name: 'Nombre',
		enableHiding: false
	},
	{
		accessorKey: 'cli_ema',
		header: () => h('div', { class: 'text-right' }, ['Email']),
		cell: ({ row }) => h('div', { class: 'text-right' }, [row.original.cli_ema]),
		name: 'Email'
	},
	{
		accessorKey: 'cli_tel',
		header: () => h('div', { class: 'text-right' }, ['Teléfono']),
		cell: ({ row }) => h('div', { class: 'text-right' }, [row.original.cli_tel]),
		name: 'Teléfono'
	},
	{
		accessorKey: 'updated_at',
		header: () => h('div', { class: 'text-right' }, ['Última actualización']),
		cell: ({ row }) =>
			h('div', { class: 'text-right' }, [new Date(row.original.updated_at).toLocaleString()]),
		name: 'Última actualización'
	},
	{
		id: 'actions',
		cell: ({ row }) => {
			return h('div', { class: 'relative' }, [
				h(DataTableDropdownClient, {
					original: row.original,
					updateForm: updateForm,
					dataRef: dataRef
				})
			]);
		},
		enableSorting: false,
		enableHiding: false
	}
];

const updateForm = useForm({
	id: -1,
	nombre: '',
	apellido: '',
	email: '',
	direccion: ''
});
const form = useForm({
	nombre: '',
	apellido: '',
	telefono: '',
	email: '',
	direccion: '',
	sexo: ''
});
const dataRef = ref(props.data);
const filters: string = 'cli_nom';

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
	<Head title="Clientes" />

	<AuthenticatedLayout>
		<template #header>
			<h2 class="text-xl font-semibold leading-tight text-gray-800">Clientes</h2>
		</template>
		<div class="px-4 py-12">
			<div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
				<DataTable
					:data="dataRef || []"
					:columns="CLIENTS_COLUMNS as unknown as ColumnDef<any>[]"
					:filters="filters || ''"
					placeholder="Buscar clientes por nombre o email"
				>
					<template #top>
						<DataTableDialogCliente :form="form" />
					</template>
				</DataTable>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
