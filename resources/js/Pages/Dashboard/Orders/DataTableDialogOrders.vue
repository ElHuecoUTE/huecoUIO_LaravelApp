<script lang="ts" setup>
import { ClientColumn, OrderProduct } from '@/components/table/columns';
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
import { Loader2, LoaderCircle, Minus, Plus, Trash } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const submit = () => {
	postForm.total = total.value;
	postForm.post(route('pedidos.store'), {
		onSuccess: () => {
			postForm.reset();
			toast({
				title: 'Pedido creado',
				description: 'El pedido ha sido creado exitosamente.',
				duration: 5000
			});

			searchForm.search = '';
			dataRef.value = {
				success: false,
				data: [],
				message: ''
			};
			productSearchForm.search = '';
			productDataRef.value = {
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
	clientId: -1,
	productIds: <
		{
			pro_id: number;
			pro_nom: string;
			pro_val: number;
			count: number;
			stock: number;
		}[]
	>[],
	total: 0
});
const total = computed(() => {
	return postForm.productIds.reduce((acc, product) => {
		return acc + product.pro_val * product.count;
	}, 0);
});

const searchForm = useForm({
	search: ''
});
const dataRef = ref<{
	success: boolean;
	data: ClientColumn[];
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

		if (postForm.clientId !== -1) {
			postForm.clientId = -1;
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
			route('buscar-cliente', {
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

const productDataRef = ref<{
	success: boolean;
	data: OrderProduct[];
	message: string;
}>({
	success: false,
	data: [],
	message: ''
});
const productSearchForm = useForm({
	search: ''
});
const searchTimeout = ref<ReturnType<typeof setTimeout> | null>(null);
const searchProduct = () => {
	if (searchTimeout) {
		clearTimeout(searchTimeout.value as ReturnType<typeof setTimeout>);
	}

	searchTimeout.value = setTimeout(async () => {
		if (!productSearchForm.search) {
			productDataRef.value = {
				success: false,
				data: [],
				message: ''
			};
			return;
		}

		if (productSearchForm.search === '') {
			productDataRef.value = {
				success: false,
				data: [],
				message: ''
			};
			return;
		}

		const response = await fetch(
			route('buscar-producto', {
				id: productSearchForm.search
			})
		);
		const data = await response.json();
		if (data.success) {
			productDataRef.value = {
				success: true,
				data: data.data,
				message: ''
			};
		} else {
			productDataRef.value = {
				success: false,
				data: [],
				message: data.message
			};
		}

		searchTimeout.value = null;
	}, 500);
};

const setActiveUser = (user: any) => {
	searchForm.search = `${user.cli_nom} ${user.cli_ape}`;
	postForm.clientId = user.cli_id;
	dataRef.value = {
		success: false,
		data: [],
		message: ''
	};
};

const addProduct = (invItem: any) => {
	const product = invItem.producto;
	const productExists = postForm.productIds.find((p) => p.pro_id === product.pro_id);
	if (productExists) {
		productExists.count++;
		productSearchForm.search = '';
		productDataRef.value = {
			success: false,
			data: [],
			message: ''
		};
		return;
	}

	postForm.productIds.push({
		pro_id: product.pro_id,
		pro_nom: product.pro_nom,
		pro_val: parseFloat(product.pro_val),
		stock: invItem.inv_stock,
		count: 1
	});
	productSearchForm.search = '';
	productDataRef.value = {
		success: false,
		data: [],
		message: ''
	};
};

const increaseQuantity = (product: any) => {
	if (product.count >= product.stock) {
		return;
	}

	product.count++;
};

const decreaseQuantity = (product: any) => {
	if (product.count <= 1) {
		return;
	}

	product.count--;
};

const removeProduct = (product: any) => {
	postForm.productIds = postForm.productIds.filter((p) => p.pro_id !== product.pro_id);
};
</script>

<template>
	<Dialog>
		<DialogTrigger>
			<Button> Crear Pedido </Button>
		</DialogTrigger>
		<DialogContent class="sm:max-w-[425px]">
			<form @submit.prevent="submit">
				<DialogHeader>
					<DialogTitle> Crear nuevo pedido </DialogTitle>
					<DialogDescription> Complete los campos para crear un nuevo pedido. </DialogDescription>
				</DialogHeader>
				<div class="relative grid gap-4 py-4">
					<div class="grid grid-cols-4 items-center gap-4">
						<Label for="cliente" class="text-right"> Cliente </Label>
						<div class="relative col-span-3 w-full">
							<Input
								id="cliente"
								v-model="searchForm.search"
								placeholder="Buscar cliente"
								@update:modelValue="searchUsers"
							/>
							<div
								v-if="searchForm.search && dataRef.data.length > 0 && !timeout"
								class="absolute top-full z-50 flex max-h-40 w-full translate-y-2 flex-col flex-nowrap overflow-y-auto rounded border bg-white"
							>
								<button
									type="button"
									v-for="user in dataRef.data"
									:key="user.cli_id"
									class="flex max-h-10 min-h-10 w-full items-center overflow-hidden overflow-ellipsis whitespace-nowrap border-b px-3 text-sm capitalize transition-colors duration-200 hover:bg-neutral-100"
									@click="setActiveUser(user)"
								>
									{{ `${user.cli_nom} ${user.cli_ape}` }}
								</button>
							</div>
							<p
								v-else-if="
									searchForm.search &&
									dataRef.data.length <= 0 &&
									!timeout &&
									postForm.clientId === -1
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

					<div class="grid grid-cols-4 items-center gap-4">
						<Label for="producto" class="text-right">Producto</Label>
						<div class="relative col-span-3 w-full">
							<Input
								id="producto"
								v-model="productSearchForm.search"
								placeholder="Buscar producto"
								@update:modelValue="searchProduct"
							/>
							<div
								v-if="productSearchForm.search && productDataRef.data.length > 0 && !searchTimeout"
								class="absolute top-full z-50 flex max-h-40 w-full translate-y-2 flex-col flex-nowrap overflow-y-auto rounded border bg-white"
							>
								<button
									type="button"
									v-for="invProd in productDataRef.data"
									:key="invProd.producto.pro_id"
									class="flex max-h-10 min-h-10 w-full items-center overflow-hidden overflow-ellipsis whitespace-nowrap border-b px-3 text-sm capitalize transition-colors duration-200 hover:bg-neutral-100"
									@click="addProduct(invProd)"
								>
									{{ `${invProd.producto.pro_nom}` }}
								</button>
							</div>
							<p
								v-else-if="
									productSearchForm.search && productDataRef.data.length <= 0 && !searchTimeout
								"
								class="absolute top-full z-50 w-full translate-y-2 overflow-y-auto rounded border bg-white px-3 py-2"
							>
								No se encontraron resultados
							</p>
							<span
								v-else-if="productSearchForm.search && searchTimeout"
								class="absolute top-full z-50 w-full translate-y-2 overflow-y-auto rounded border bg-white px-3 py-2"
							>
								<LoaderCircle class="mx-auto h-6 w-6 animate-spin" />
							</span>
						</div>
					</div>
					<div class="grid grid-cols-4 items-start gap-4" v-if="postForm.productIds.length > 0">
						<Label for="nombre" class="mt-4 text-right">Cantidad</Label>
						<div class="relative col-span-3 flex w-full flex-col justify-start gap-y-1">
							<div
								v-for="product in postForm.productIds"
								:key="product.pro_id"
								class="flex w-full items-center justify-between space-x-2"
							>
								<p
									class="grow overflow-hidden text-ellipsis whitespace-nowrap rounded-md border px-4 py-3.5 text-sm font-medium leading-none"
								>
									{{ product.count }}x
									{{ product.pro_nom }}
								</p>
								<div class="flex items-center gap-x-2">
									<Button
										type="button"
										v-if="product.count > 1"
										size="icon"
										@click="decreaseQuantity(product)"
									>
										<Minus class="h-4 w-4" />
									</Button>
									<Button type="button" v-else size="icon" @click="removeProduct(product)">
										<Trash class="h-4 w-4" />
									</Button>
									<Button
										v-if="product.count < product.stock"
										type="button"
										size="icon"
										@click="increaseQuantity(product)"
									>
										<Plus class="h-4 w-4" />
									</Button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<DialogFooter>
					<Button
						type="submit"
						:disabled="
							postForm.processing || postForm.clientId === -1 || postForm.productIds.length === 0
						"
					>
						<Loader2 v-if="postForm.processing" class="mr-2 h-4 w-4 animate-spin" />
						Crear pedido
					</Button>
				</DialogFooter>
			</form>
		</DialogContent>
	</Dialog>
</template>
