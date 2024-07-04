<script setup lang="ts">
import AlertDialogItem from '@/components/table/AlertDialogItem.vue';
import { ProductTypeColumn } from '@/components/table/columns';
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
import { MoreHorizontal } from 'lucide-vue-next';

const props = defineProps<{
	original: ProductTypeColumn;
	dataRef: any;
}>();

const updateForm = useForm({
	id: -1,
	nombre: ''
});

const deleteForm = useForm({
	id: -1
});

const deleteSubmit = async () => {
	if (deleteForm.id === -1) {
		toast({
			title: 'Error al eliminar tipo de producto',
			description: 'No se seleccionó un tipo de producto válido.',
			duration: 5000,
			variant: 'destructive'
		});
		return;
	}

	deleteForm.delete(route('tipos-productos.destroy', deleteForm.id), {
		preserveScroll: true,
		onSuccess: () => {
			toast({
				title: 'Tipo de producto eliminado',
				description: 'El tipo de producto ha sido eliminado exitosamente.',
				duration: 5000
			});

			props.dataRef.value = props.dataRef.value.filter(
				(item: any) => item.tip_pro_id !== deleteForm.id
			);
		},
		onError: () => {
			toast({
				title: 'Error al eliminar tipo de producto',
				description: 'Ha ocurrido un error al intentar eliminar el tipo de producto.',
				duration: 5000,
				variant: 'destructive'
			});
		}
	});
};

const updateSubmit = async () => {
	if (updateForm.id === -1) {
		toast({
			title: 'Error al actualizar tipo de producto',
			description: 'No se seleccionó un tipo de producto válido.',
			duration: 5000,
			variant: 'destructive'
		});
		return;
	}

	if (updateForm.nombre === props.original.tip_pro_nom) {
		toast({
			title: 'Error al actualizar tipo de producto',
			description: 'El nombre del tipo de producto no ha cambiado.',
			duration: 5000,
			variant: 'destructive'
		});
		return;
	}

	updateForm.put(route('tipos-productos.update', { id: updateForm.id }), {
		preserveScroll: true,
		onSuccess: () => {
			toast({
				title: 'Tipo de producto actualizado',
				description: 'El tipo de producto ha sido actualizado exitosamente.',
				duration: 5000
			});

			const idx = props.dataRef.value.findIndex((item: any) => item.tip_pro_id === updateForm.id);
			props.dataRef.value[idx].tip_pro_nom = updateForm.nombre;
		},
		onError: () => {
			toast({
				title: 'Error al actualizar tipo de producto',
				description: 'Ha ocurrido un error al intentar actualizar el tipo de producto.',
				duration: 5000,
				variant: 'destructive'
			});
		}
	});
};

const openedUpdateForm = () => {
	updateForm.id = props.original.tip_pro_id;
	updateForm.nombre = props.original.tip_pro_nom;
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
			<DrawerItem
				dropdownText="Ver tipo"
				:title="original.tip_pro_nom"
				description="Detalles del tipo de producto."
			>
			</DrawerItem>

			<DropdownMenuSeparator />
			<!-- Editar producto -->
			<DialogItem
				dropdownText="Editar tipo"
				title="Editar tipo de producto"
				description="Complete los campos para actualizar el tipo de producto."
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
				</div>
			</DialogItem>

			<!-- Eliminar cliente -->
			<AlertDialogItem
				dropdownText="Eliminar tipo"
				title="Eliminar tipo de producto"
				description="Esta acción no se puede deshacer. Esto eliminará permanentemente el tipo de producto y todos los productos asociados."
				cancel="Cancelar"
				action="Eliminar"
				@submit="deleteSubmit"
				@opened="() => (deleteForm.id = original.tip_pro_id)"
				@closed="() => (deleteForm.id = -1)"
			>
			</AlertDialogItem>
		</DropdownMenuContent>
	</DropdownMenu>
</template>
