<script setup lang="ts">
import { InventoryColumn } from '@/components/table/columns';
import DialogItem from '@/components/table/DialogItem.vue';
import DrawerItem from '@/components/table/DrawerItem.vue';
import { Button } from '@/components/ui/button';
import {
	DropdownMenu,
	DropdownMenuContent,
	DropdownMenuLabel,
	DropdownMenuSeparator,
	DropdownMenuTrigger
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { toast } from '@/components/ui/toast';
import { useForm } from '@inertiajs/vue3';
import { Check, DollarSign, Layers3, MoreHorizontal } from 'lucide-vue-next';

const props = defineProps<{
	original: InventoryColumn;
	dataRef: any;
	postForm: ReturnType<
		typeof useForm<{
			id: number;
			stock: number;
		}>
	>;
}>();

const postSubmit = () => {
	props.postForm.post(route('inventario.store'), {
		onSuccess: () => {
			toast({
				title: 'Stock agregado',
				description: 'El stock ha sido agregado exitosamente.',
				duration: 5000
			});

			const idx = props.dataRef.value.findIndex((item: any) => item.inv_id === props.postForm.id);
			props.dataRef.value[idx].inv_stock += props.postForm.stock;
		},
		onError: (errors) => {
			toast({
				title: 'Error al agregar stock',
				description:
					(Object.values(errors)[0] as string) ||
					'Por favor, revise los campos e intente de nuevo.',
				duration: 5000,
				variant: 'destructive'
			});
		}
	});
};

const openedUpdateForm = () => {
	props.postForm.id = props.original.inv_id;
};
</script>

<template>
	<DropdownMenu>
		<DropdownMenuTrigger as-child>
			<Button variant="ghost" class="h-8 w-8 p-0">
				<span class="sr-only">Abrir menu</span>
				<MoreHorizontal class="h-4 w-4" />
			</Button>
		</DropdownMenuTrigger>
		<DropdownMenuContent align="end">
			<DropdownMenuLabel>Acciones</DropdownMenuLabel>
			<!-- Ver inventario -->
			<DrawerItem
				dropdownText="Ver inventario"
				:title="original.producto.pro_nom"
				:description="original.producto.tipo_producto?.tip_pro_nom"
			>
				<div class="relative w-full p-4 pb-0">
					<div class="grid w-full grid-cols-2 gap-2">
						<div class="flex w-full items-center space-x-4 rounded-md border p-4">
							<Layers3 />
							<div class="flex-1 space-y-1">
								<p class="text-sm font-medium leading-none">Stock</p>
								<p class="text-sm text-muted-foreground">
									{{ original.inv_stock }}
								</p>
							</div>
						</div>
						<div class="flex w-full items-center space-x-4 rounded-md border p-4">
							<DollarSign />
							<div class="flex-1 space-y-1">
								<p class="text-sm font-medium leading-none">Valor</p>
								<p class="text-sm text-muted-foreground">${{ original.producto.pro_val }}</p>
							</div>
						</div>
						<div class="col-span-2 flex w-full items-center space-x-4 rounded-md border p-4">
							<Check />
							<div class="flex-1 space-y-1">
								<p class="text-sm font-medium leading-none">Estado</p>
								<p class="text-sm text-muted-foreground">{{ original.estado_inventario.est_inv_des }}</p>
							</div>
						</div>
					</div>
				</div>
			</DrawerItem>

			<DropdownMenuSeparator />
			<!-- Agregar inv -->
			<DialogItem
				dropdownText="Agregar stock"
				:title="`Agregar stock a ${original.producto.pro_nom}`"
				description="Complete los campos para agregar stock a este producto."
				action="Agregar"
				:disabled="postForm.processing"
				@submit="postSubmit"
				@opened="() => openedUpdateForm()"
			>
				<div class="grid gap-4 py-4">
					<div class="grid grid-cols-4 items-center gap-4">
						<Label for="stock" class="text-right">Stock</Label>
						<Input
							id="stock"
							class="col-span-3"
							required
							v-model="postForm.stock"
							:disabled="postForm.processing"
							type="number"
							min="0"
							max="99999"
						/>
					</div>
				</div>
			</DialogItem>
		</DropdownMenuContent>
	</DropdownMenu>
</template>
