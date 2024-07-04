<script lang="ts" setup>
import { OrderColumn } from '@/components/table/columns';
import { Button } from '@/components/ui/button';
import {
	Dialog,
	DialogContent,
	DialogDescription,
	DialogFooter,
	DialogHeader,
	DialogTitle,
	DialogTrigger
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { toast } from '@/components/ui/toast';
import { useForm } from '@inertiajs/vue3';
import { Loader2, LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const submit = () => {
	postForm.post(route('ventas.store'), {
		onSuccess: () => {
			postForm.reset();
			toast({
				title: 'Venta creada',
				description: 'La venta ha sido creada exitosamente.',
				duration: 5000
			});

			searchForm.search = '';
			dataRef.value = {
				success: false,
				data: [],
				message: ''
			};
		},
		onError: (errors: any) => {
			toast({
				title: 'Error al crear el pedido',
				description:
					(Object.values(errors)[0] as string) ||
					'Por favor, revise los campos e intente de nuevo.',
				duration: 5000,
				variant: 'destructive'
			});
		}
	});
};

const postForm = useForm({
	pedidoId: -1
});

const searchForm = useForm({
	search: ''
});
const dataRef = ref<{
	success: boolean;
	data: OrderColumn[];
	message: string;
}>({
	success: false,
	data: [],
	message: ''
});
let timeout = ref<ReturnType<typeof setTimeout> | null>(null);
const searchUsers = async () => {
	if (timeout) {
		clearTimeout(timeout.value as ReturnType<typeof setTimeout>);
	}

	timeout.value = setTimeout(async () => {
		if (!searchForm.search) {
			dataRef.value = {
				success: false,
				data: [],
				message: ''
			};
			return;
		}

		if (postForm.pedidoId === -1) {
			postForm.pedidoId = -1;
		}

		if (searchForm.search === '') {
			dataRef.value = {
				success: false,
				data: [],
				message: ''
			};
			return;
		}

		const response = await fetch(
			route('buscar-venta', {
				id: searchForm.search
			})
		);
		const data = await response.json();
		if (data.success) {
			dataRef.value = {
				success: true,
				data: data.data,
				message: ''
			};
		} else {
			dataRef.value = {
				success: false,
				data: [],
				message: data.message
			};
		}

		timeout.value = null;
	}, 500);
};

const setActiveOrder = (order: OrderColumn) => {
	searchForm.search = order.ped_id.toString() + ' - $' + order.ped_tot;
	postForm.pedidoId = order.ped_id;

	dataRef.value = {
		success: false,
		data: [],
		message: ''
	};
};
</script>

<template>
	<Dialog>
		<DialogTrigger>
			<Button> Crear Venta </Button>
		</DialogTrigger>
		<DialogContent class="sm:max-w-[425px]">
			<form @submit.prevent="submit">
				<DialogHeader>
					<DialogTitle> Crear nueva venta </DialogTitle>
					<DialogDescription> Complete los campos para crear una nueva venta. </DialogDescription>
				</DialogHeader>
				<div class="relative grid gap-4 py-4">
					<div class="grid grid-cols-4 items-center gap-4">
						<Label for="venta" class="text-right"> Pedido </Label>
						<div class="relative col-span-3 w-full">
							<Input
								id="venta"
								v-model="searchForm.search"
								placeholder="Buscar pedido por ID"
								@update:modelValue="searchUsers"
							/>
							<div
								v-if="searchForm.search && dataRef.data.length > 0 && !timeout"
								class="absolute top-full z-50 flex max-h-40 w-full translate-y-2 flex-col flex-nowrap overflow-y-auto rounded border bg-white"
							>
								<button
									type="button"
									v-for="pedido in dataRef.data"
									:key="pedido.ped_id"
									class="flex max-h-10 min-h-10 w-full items-center overflow-hidden overflow-ellipsis whitespace-nowrap border-b px-3 text-sm capitalize transition-colors duration-200 hover:bg-neutral-100"
									@click="setActiveOrder(pedido)"
								>
									{{ pedido.ped_id }} - ${{ pedido.ped_tot }}
								</button>
							</div>
							<p
								v-else-if="
									searchForm.search &&
									dataRef.data.length <= 0 &&
									!timeout &&
									postForm.pedidoId === -1
								"
								class="absolute top-full z-50 w-full translate-y-2 overflow-y-auto rounded border bg-white px-3 py-2"
							>
								No se encontraron resultados
							</p>
							<span
								v-else-if="searchForm.search && timeout"
								class="absolute top-full z-50 w-full translate-y-2 overflow-y-auto rounded border bg-white px-3 py-2"
							>
								<LoaderCircle class="mx-auto h-6 w-6 animate-spin" />
							</span>
						</div>
					</div>
				</div>
				<DialogFooter>
					<Button type="submit" :disabled="postForm.processing || postForm.pedidoId === -1">
						<Loader2 v-if="postForm.processing" class="mr-2 h-4 w-4 animate-spin" />
						Crear venta
					</Button>
				</DialogFooter>
			</form>
		</DialogContent>
	</Dialog>
</template>
