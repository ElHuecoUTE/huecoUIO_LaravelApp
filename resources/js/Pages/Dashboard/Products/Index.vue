<script setup lang="ts">
import { ProductColumn } from '@/components/table/columns';
import DataTable from '@/components/table/DataTable.vue';
import {
	AlertDialog,
	AlertDialogAction,
	AlertDialogCancel,
	AlertDialogContent,
	AlertDialogDescription,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogTitle,
	AlertDialogTrigger
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { toast } from '@/components/ui/toast';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DataTableDialogProduct from '@/Pages/Dashboard/Products/DataTableDialogProduct.vue';
import DataTableDropdownProduct from '@/Pages/Dashboard/Products/DataTableDropdownProduct.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown, Trash } from 'lucide-vue-next';
import { h, ref, watch } from 'vue';

const props = defineProps<{
	data?: any;
	result?: any;
	product_type?: any;
	product_state?: any;
}>();

type CustomColumnDef =
	| ColumnDef<ProductColumn>
	| {
			name: string;
	  };

const productTypeRef = ref(props.product_type);
const productStateRef = ref(props.product_state);
const dataRef = ref(props.data);
const selectedRows = ref<{
	rows: any[];
	flatRows: any[];
	rowsById: Record<string, any>;
}>({
	rows: [],
	flatRows: [],
	rowsById: {}
});
const CLIENTS_COLUMNS: CustomColumnDef[] = [
	{
		id: 'select',
		header: ({ table }) =>
			h(Checkbox, {
				checked:
					table.getIsAllPageRowsSelected() ||
					(table.getIsSomePageRowsSelected() && 'indeterminate'),
				'onUpdate:checked': (value) => {
					table.toggleAllPageRowsSelected(!!value);
					selectedRows.value = table.getSelectedRowModel();
				},
				ariaLabel: 'Seleccionar todas las filas'
			}),
		cell: ({ row, table }) =>
			h(Checkbox, {
				checked: row.getIsSelected(),
				'onUpdate:checked': (value) => {
					row.toggleSelected(!!value);
					selectedRows.value = table.getSelectedRowModel();
				},
				ariaLabel: 'Seleccionar fila'
			}),
		enableSorting: false,
		enableHiding: false
	},
	{
		accessorKey: 'pro_nom',
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
		cell: ({ row }) => h('div', { class: 'ml-4' }, [row.original.pro_nom]),
		name: 'Nombre',
		enableHiding: false
	},
	{
		accessorKey: 'tip_pro_nom',
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
		cell: ({ row }) => h('div', { class: 'ml-4' }, [row.original.tipo_producto!.tip_pro_nom]),
		enableSorting: true,
		name: 'Tipo'
	},
	{
		accessorKey: 'pro_val',
		header: ({ column }) => {
			return h(
				Button,
				{
					variant: 'ghost',
					onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
				},
				() => ['Valor', h(ArrowUpDown, { class: 'w-4 h-4 ml-2' })]
			);
		},
		cell: ({ row }) => h('div', { class: 'ml-4' }, ['$' + row.original.pro_val]),
		enableSorting: true,
		name: 'Valor'
	},
	{
		accessorKey: 'pro_est',
		header: ({ column }) => {
			return h(
				Button,
				{
					variant: 'ghost',
					onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
				},
				() => ['Estado', h(ArrowUpDown, { class: 'w-4 h-4 ml-2' })]
			);
		},
		cell: ({ row }) => h('div', { class: 'ml-4' }, [row.original.estado_producto!.est_pro_nom]),
		name: 'Estado'
	},
	{
		id: 'actions',
		cell: ({ row }) => {
			return h('div', { class: 'relative float-end' }, [
				h(DataTableDropdownProduct, {
					original: row.original,
					tipo: row.original.tipo_producto!.tip_pro_nom,
					estado: row.original.estado_producto!.est_pro_nom,
					product_type: productTypeRef.value,
					product_state: productStateRef.value,
					deleteForm: deleteForm,
					updateForm: updateForm,
					dataRef: dataRef
				})
			]);
		},
		enableSorting: false,
		enableHiding: false
	}
];

const deleteForm = useForm({
	id: -1
});
const deleteAllForm = useForm({
	ids: <number[]>[]
});
const updateForm = useForm({
	id: -1,
	nombre: '',
	valor: 0,
	tipo: null,
	estado: null
});
const form = useForm({
	nombre: '',
	valor: 0,
	tipo: null
});
const filters: string = 'pro_nom';

const deleteAll = () => {
	for (const row of selectedRows.value.rows) {
		deleteAllForm.ids.push(row.original.pro_id);
	}

	deleteAllForm.delete(route('productos.destroy.all'), {
		preserveScroll: true,
		onSuccess: () => {
			toast({
				title: 'Productos eliminados',
				description: `Se han eliminado ${deleteAllForm.ids.length} productos.`,
				duration: 5000
			});
			dataRef.value = dataRef.value.filter((row: any) => !deleteAllForm.ids.includes(row.pro_id));
			deleteAllForm.ids = [];
		},
		onError: () => {
			toast({
				title: 'Error al eliminar los productos',
				description: 'Por favor, revise los campos e intente de nuevo.',
				duration: 5000,
				variant: 'destructive'
			});
		}
	});
};

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
	<Head title="Productos" />

	<AuthenticatedLayout>
		<template #header>
			<h2 class="text-xl font-semibold leading-tight text-gray-800">Productos</h2>
		</template>

		<div class="px-4 py-12">
			<div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
				<DataTable
					:data="dataRef || []"
					:columns="CLIENTS_COLUMNS as unknown as ColumnDef<any>[]"
					:filters="filters || ''"
					placeholder="Buscar productos por nombre"
					@update:selectedRows="
						(table) => {
							table.toggleAllRowsSelected(selectedRows.rows.length === dataRef.length);
							selectedRows = table.getSelectedRowModel();
						}
					"
				>
					<template #top>
						<div class="flex justify-end gap-x-2">
							<AlertDialog>
								<AlertDialogTrigger as-child>
									<Button
										v-if="selectedRows.rows.length > 0"
										size="icon"
										variant="destructive"
										type="submit"
									>
										<Trash class="h-4 w-4" />
									</Button>
								</AlertDialogTrigger>
								<AlertDialogContent>
									<AlertDialogHeader>
										<AlertDialogTitle class="text-lg font-semibold"
											>Eliminar {{ selectedRows.rows.length }} productos(s)</AlertDialogTitle
										>
										<AlertDialogDescription>
											¿Estás seguro de que deseas eliminar
											{{ selectedRows.rows.length }} productos(s)? Esta acción no se puede deshacer
											y se eliminarán permanentemente.
										</AlertDialogDescription>
									</AlertDialogHeader>
									<AlertDialogFooter>
										<AlertDialogCancel>Cancelar</AlertDialogCancel>
										<AlertDialogAction
											variant="destructive"
											@click="deleteAll"
											:disabled="deleteAllForm.processing"
										>
											Eliminar
										</AlertDialogAction>
									</AlertDialogFooter>
								</AlertDialogContent>
							</AlertDialog>
							<DataTableDialogProduct
								:form="form"
								:types="productTypeRef"
								v-if="productTypeRef.length > 0"
							/>
						</div>
					</template>
				</DataTable>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
