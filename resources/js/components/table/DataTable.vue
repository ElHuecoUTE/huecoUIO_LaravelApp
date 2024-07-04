<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import {
	DropdownMenu,
	DropdownMenuCheckboxItem,
	DropdownMenuContent,
	DropdownMenuTrigger
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import {
	Table,
	TableBody,
	TableCell,
	TableHead,
	TableHeader,
	TableRow
} from '@/components/ui/table';
import { cn, valueUpdater } from '@/lib/utils';
import {
	ColumnDef,
	ColumnFiltersState,
	FlexRender,
	getCoreRowModel,
	getFilteredRowModel,
	getPaginationRowModel,
	getSortedRowModel,
	SortingState,
	useVueTable,
	VisibilityState
} from '@tanstack/vue-table';
import { ChevronDown } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const props = defineProps<{
	data: any[];
	columns: ColumnDef<any>[];
	filters: string;
	placeholder?: string;
}>();
const emit = defineEmits<{
	(e: 'update:selectedRows', value: any): void;
}>();

const sorting = ref<SortingState>([]);
const columnFilters = ref<ColumnFiltersState>([]);
const columnVisibility = ref<VisibilityState>({});
const rowSelection = ref({});

const table = useVueTable({
	get data() {
		return props.data;
	},
	columns: props.columns,
	getCoreRowModel: getCoreRowModel(),
	getPaginationRowModel: getPaginationRowModel(),
	getSortedRowModel: getSortedRowModel(),
	getFilteredRowModel: getFilteredRowModel(),
	onSortingChange: (updaterOrValue) => valueUpdater(updaterOrValue, sorting),
	onColumnFiltersChange: (updaterOrValue) => valueUpdater(updaterOrValue, columnFilters),
	onColumnVisibilityChange: (updaterOrValue) => valueUpdater(updaterOrValue, columnVisibility),
	onRowSelectionChange: (updaterOrValue) => valueUpdater(updaterOrValue, rowSelection),
	state: {
		get sorting() {
			return sorting.value;
		},
		get columnFilters() {
			return columnFilters.value;
		},
		get columnVisibility() {
			return columnVisibility.value;
		},
		get rowSelection() {
			return rowSelection.value;
		}
	}
});

const setFilterValue = (key: string, value: any) => {
	table.getColumn(key)?.setFilterValue(value);
};

watch(
	() => props.data,
	() => {
		emit('update:selectedRows', table);
	}
);
</script>

<template>
	<div class="flex w-full flex-col gap-y-6">
		<div class="flex items-center justify-between gap-x-6">
			<Input
				class="max-w-sm"
				:placeholder="placeholder || 'Buscar...'"
				:model-value="table.getColumn(filters)?.getFilterValue() as string"
				@update:model-value="setFilterValue(filters, $event)"
			/>
			<div class="flex items-center space-x-2">
				<DropdownMenu>
					<DropdownMenuTrigger as-child>
						<Button variant="outline" class="ml-auto">
							Opciones <ChevronDown class="ml-2 h-4 w-4" />
						</Button>
					</DropdownMenuTrigger>
					<DropdownMenuContent align="end">
						<DropdownMenuCheckboxItem
							v-for="column in table.getAllColumns().filter((column) => column.getCanHide())"
							:key="column.id"
							class="capitalize"
							:checked="column.getIsVisible()"
							@update:checked="
								(value) => {
									column.toggleVisibility(!!value);
								}
							"
						>
							{{ (column.columnDef as any).name }}
						</DropdownMenuCheckboxItem>
					</DropdownMenuContent>
				</DropdownMenu>
				<slot name="top"></slot>
			</div>
		</div>

		<div class="rounded-md border">
			<Table>
				<TableHeader>
					<TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
						<TableHead
							v-for="header in headerGroup.headers"
							:key="header.id"
							:data-pinned="header.column.getIsPinned()"
							:class="
								cn(
									{ 'sticky bg-background/95': header.column.getIsPinned() },
									header.column.getIsPinned() === 'left' ? 'left-0' : 'right-0'
								)
							"
						>
							<FlexRender
								v-if="!header.isPlaceholder"
								:render="header.column.columnDef.header"
								:props="header.getContext()"
							/>
						</TableHead>
					</TableRow>
				</TableHeader>
				<TableBody>
					<template v-if="table.getRowModel().rows?.length">
						<TableRow
							v-for="row in table.getRowModel().rows"
							:key="row.id"
							:data-state="row.getIsSelected() && 'selected'"
						>
							<TableCell
								v-for="cell in row.getVisibleCells()"
								:key="cell.id"
								:data-pinned="cell.column.getIsPinned()"
								:class="
									cn(
										{ 'sticky bg-background/95': cell.column.getIsPinned() },
										cell.column.getIsPinned() === 'left' ? 'left-0' : 'right-0'
									)
								"
							>
								<FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
							</TableCell>
						</TableRow>
					</template>

					<TableRow v-else>
						<TableCell :colspan="columns.length" class="h-24 text-center">
							No hay resultados.
						</TableCell>
					</TableRow>
				</TableBody>
			</Table>
		</div>
		<div class="flex items-center justify-end space-x-2">
			<div class="flex-1 text-sm text-muted-foreground">
				{{ table.getFilteredSelectedRowModel().rows.length }} de
				{{ table.getFilteredRowModel().rows.length }} filas seleccionada(s)
			</div>
			<div class="space-x-2">
				<Button
					variant="outline"
					size="sm"
					:disabled="!table.getCanPreviousPage()"
					@click="table.previousPage()"
				>
					Anterior
				</Button>
				<Button
					variant="outline"
					size="sm"
					:disabled="!table.getCanNextPage()"
					@click="table.nextPage()"
				>
					Siguiente
				</Button>
			</div>
		</div>
	</div>
</template>
