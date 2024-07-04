<script setup lang="ts">
import AlertDialogItem from '@/components/table/AlertDialogItem.vue';
import { ProductColumn } from '@/components/table/columns';
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
import {
	Select,
	SelectContent,
	SelectGroup,
	SelectItem,
	SelectTrigger,
	SelectValue
} from '@/components/ui/select';
import { toast } from '@/components/ui/toast';
import { useForm } from '@inertiajs/vue3';
import { Check, DollarSign, MoreHorizontal } from 'lucide-vue-next';

const props = defineProps<{
	original: ProductColumn;
	tipo: string;
	estado: string;
	product_type: {
		tip_pro_id: number;
		tip_pro_nom: string;
	}[];
	product_state: {
		est_pro_id: number;
		est_pro_nom: string;
	}[];
	updateForm: ReturnType<
		typeof useForm<{
			id: number;
			nombre: string;
			valor: number;
			estado: string | number | null;
			tipo: string | number | null;
		}>
	>;
	deleteForm: ReturnType<typeof useForm<{ id: number }>>;
	dataRef: any;
}>();

const deleteSubmit = async () => {
	if (props.deleteForm.id <= -1) {
		toast({
			title: 'Error al eliminar',
			description: 'No se ha seleccionado un producto para eliminar.',
			duration: 5000,
			variant: 'destructive'
		});
		return;
	}

	props.deleteForm.delete(route('productos.destroy', { id: props.deleteForm.id }), {
		onSuccess: () => {
			toast({
				title: 'Producto eliminado',
				description: 'El producto ha sido eliminado exitosamente.',
				duration: 5000
			});

			props.dataRef.value = props.dataRef.value.filter(
				(item: any) => item.pro_id !== props.deleteForm.id
			);
			props.deleteForm.id = -1;
		},
		onError: (errors) => {
			toast({
				title: 'Error al eliminar el cliente',
				description: Object.values(errors)[0] || 'Por favor, revise los campos e intente de nuevo.',
				duration: 5000,
				variant: 'destructive'
			});
		}
	});
};

const updateSubmit = async () => {
	if (props.updateForm.id <= -1) {
		toast({
			title: 'Error al actualizar',
			description: 'No se ha seleccionado un producto para actualizar.',
			duration: 5000,
			variant: 'destructive'
		});
		return;
	}

	props.updateForm.estado =
		props.updateForm.estado === null ? null : parseInt(props.updateForm.estado as string);
	props.updateForm.tipo =
		props.updateForm.tipo === null ? null : parseInt(props.updateForm.tipo as string);

	props.updateForm.put(route('productos.update', {}), {
		onSuccess: () => {
			toast({
				title: 'Producto actualizado',
				description: 'El producto ha sido actualizado exitosamente.',
				duration: 5000
			});

			props.updateForm.estado = (props.updateForm.estado as number).toString();
			props.updateForm.tipo = (props.updateForm.tipo as number).toString();

			const index = props.dataRef.value.findIndex(
				(item: any) => item.pro_id === props.updateForm.id
			);

			if (index < 0) {
				return;
			}
			props.dataRef.value[index].pro_nom = props.updateForm.nombre;
			props.dataRef.value[index].pro_val = parseFloat(props.updateForm.valor as any).toFixed(2);
			props.dataRef.value[index].fk_est_pro_id = props.updateForm.estado;
			props.dataRef.value[index].fk_tip_pro_id = props.updateForm.tipo;
		},
		onError: (errors: any) => {
			toast({
				title: 'Error al actualizar el producto',
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
	props.updateForm.id = props.original.pro_id;
	props.updateForm.nombre = props.original.pro_nom;
	props.updateForm.valor = props.original.pro_val;
	props.updateForm.estado = props.original.fk_est_pro_id.toString();
	props.updateForm.tipo = props.original.fk_tip_pro_id.toString();
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
			<!-- Ver cliente -->
			<DrawerItem dropdownText="Ver producto" :title="original.pro_nom" :description="tipo">
				<div class="relative w-full p-4 pb-0">
					<div class="grid w-full grid-cols-2 gap-2">
						<div class="flex w-full items-center space-x-4 rounded-md border p-4">
							<DollarSign />
							<div class="flex-1 space-y-1">
								<p class="text-sm font-medium leading-none">Valor</p>
								<p class="text-sm text-muted-foreground">
									{{ original.pro_val }}
								</p>
							</div>
						</div>
						<div class="flex w-full items-center space-x-4 rounded-md border p-4">
							<Check />
							<div class="flex-1 space-y-1">
								<p class="text-sm font-medium leading-none">Estado</p>
								<p class="text-sm text-muted-foreground">
									{{ estado }}
								</p>
							</div>
						</div>
					</div>
				</div>
			</DrawerItem>

			<DropdownMenuSeparator />
			<!-- Editar producto -->
			<DialogItem
				dropdownText="Editar producto"
				title="Editar producto"
				description="Complete los campos para actualizar el producto."
				action="Guardar"
				:disabled="updateForm.processing"
				@submit="updateSubmit"
				@opened="() => openedUpdateForm()"
			>
				<div class="grid gap-4 py-4">
					<div class="grid grid-cols-4 items-center gap-4">
						<Label for="nombre" class="text-right"> Nombre </Label>
						<Input
							id="nombre"
							class="col-span-3"
							required
							v-model="updateForm.nombre"
							:disabled="updateForm.processing"
						/>
					</div>
					<div class="grid grid-cols-4 items-center gap-4">
						<Label for="valor" class="text-right"> Valor </Label>
						<Input
							id="valor"
							class="col-span-3"
							required
							v-model="updateForm.valor"
							:disabled="updateForm.processing"
							type="number"
							step="0.01"
						/>
					</div>
					<div
						v-if="typeof updateForm.estado === 'string' || updateForm.estado === null"
						class="grid grid-cols-4 items-center gap-4"
					>
						<Label for="estado" class="text-right"> Estado </Label>
						<Select v-model="updateForm.estado" :disabled="updateForm.processing" required>
							<SelectTrigger class="col-span-3">
								<SelectValue placeholder="Seleccione un estado" />
							</SelectTrigger>
							<SelectContent>
								<SelectGroup>
									<SelectItem
										v-for="state in product_state"
										:key="state.est_pro_id"
										:value="state.est_pro_id.toString()"
									>
										{{ state.est_pro_nom }}
									</SelectItem>
								</SelectGroup>
							</SelectContent>
						</Select>
					</div>
					<div
						v-if="typeof updateForm.tipo === 'string' || updateForm.tipo === null"
						class="grid grid-cols-4 items-center gap-4"
					>
						<Label for="tipo" class="text-right"> Tipo </Label>
						<Select v-model="updateForm.tipo" :disabled="updateForm.processing" required>
							<SelectTrigger class="col-span-3">
								<SelectValue placeholder="Seleccione un tipo de producto" />
							</SelectTrigger>
							<SelectContent>
								<SelectGroup>
									<SelectItem
										v-for="type in product_type"
										:key="type.tip_pro_id"
										:value="type.tip_pro_id.toString()"
									>
										{{ type.tip_pro_nom }}
									</SelectItem>
								</SelectGroup>
							</SelectContent>
						</Select>
					</div>
				</div>
			</DialogItem>

			<!-- Eliminar cliente -->
			<AlertDialogItem
				dropdownText="Eliminar producto"
				title="Eliminar producto"
				description="Esta acción no se puede deshacer. Esto eliminará permanentemente el producto del servidor."
				cancel="Cancelar"
				action="Eliminar"
				@submit="deleteSubmit"
				@opened="() => (props.deleteForm.id = props.original.pro_id)"
				@closed="() => (props.deleteForm.id = -1)"
			>
			</AlertDialogItem>
		</DropdownMenuContent>
	</DropdownMenu>
</template>
