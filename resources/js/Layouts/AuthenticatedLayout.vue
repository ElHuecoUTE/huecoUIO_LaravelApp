<script setup lang="ts">
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import { Button } from '@/components/ui/button';
import {
	DropdownMenu,
	DropdownMenuContent,
	DropdownMenuGroup,
	DropdownMenuItem,
	DropdownMenuLabel,
	DropdownMenuSeparator,
	DropdownMenuShortcut,
	DropdownMenuTrigger
} from '@/components/ui/dropdown-menu';
import {
	NavigationMenu,
	NavigationMenuContent,
	NavigationMenuItem,
	NavigationMenuLink,
	NavigationMenuList,
	NavigationMenuTrigger
} from '@/components/ui/navigation-menu';
import ListItem from '@/components/ui/navigation-menu/ListItem.vue';
import Toaster from '@/components/ui/toast/Toaster.vue';
import { Link } from '@inertiajs/vue3';
import {
	BadgeDollarSign,
	Box,
	ClipboardPlus,
	LogOut,
	PackageCheck,
	ReceiptText,
	Settings,
	ShoppingCart,
	User,
	UserSearch
} from 'lucide-vue-next';
</script>

<template>
	<div>
		<div class="min-h-screen bg-gray-100">
			<nav class="border-b border-gray-100 bg-white">
				<!-- Primary Navigation Menu -->
				<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
					<div class="flex h-16 justify-between">
						<div class="flex">
							<!-- Logo -->
							<div class="flex shrink-0 items-center">
								<Link :href="route('dashboard')" class="flex items-center gap-x-6">
									<ApplicationLogo class="block h-14 w-auto fill-current text-gray-800" />
									<h1 class="hidden text-2xl font-semibold leading-tight text-gray-800 md:block">
										Hueco UTE
									</h1>
								</Link>
							</div>
						</div>

						<div class="flex items-center gap-x-2">
							<NavigationMenu>
								<NavigationMenuList>
									<NavigationMenuItem>
										<NavigationMenuTrigger> Menu </NavigationMenuTrigger>
										<NavigationMenuContent>
											<ul
												class="grid gap-3 p-6 md:w-[400px] lg:w-[500px] lg:grid-cols-[minmax(0,.75fr)_minmax(0,1fr)]"
											>
												<ListItem :href="route('clientes')" title="Clientes">
													Maneja los clientes existentes y crea nuevos.
												</ListItem>
												<ListItem :href="route('productos')" title="Productos">
													Crea y maneja los productos que se venden.
												</ListItem>
												<ListItem :href="route('tipos-productos')" title="Tipo de Productos">
													Modifica los tipos de productos existentes.
												</ListItem>
											</ul>
										</NavigationMenuContent>
									</NavigationMenuItem>

									<NavigationMenuItem>
										<NavigationMenuTrigger> Inventario </NavigationMenuTrigger>
										<NavigationMenuContent>
											<ul
												class="grid gap-3 p-6 md:w-[400px] lg:w-[500px] lg:grid-cols-[minmax(0,.75fr)_minmax(0,1fr)]"
											>
												<ListItem :href="route('inventario')" title="Inventario">
													Agrega stock a los productos.
												</ListItem>
												<ListItem :href="route('pedidos')" title="Pedidos">
													Revisa los pedidos que se han creado.
												</ListItem>
												<ListItem :href="route('ventas')" title="Ventas">
													Crea nuevas ventas segun los pedidos.
												</ListItem>
											</ul>
										</NavigationMenuContent>
									</NavigationMenuItem>

									<NavigationMenuItem>
										<NavigationMenuTrigger> Informes </NavigationMenuTrigger>
										<NavigationMenuContent>
											<ul
												class="grid gap-3 p-6 md:w-[400px] lg:w-[500px] lg:grid-cols-[minmax(0,.75fr)_minmax(0,1fr)]"
											>
												<ListItem :href="route('facturas')" title="Facturas">
													Extrae facturas de ventas, pedidos y productos.
												</ListItem>
												<ListItem :href="route('reportes')" title="Reportes">
													Revisa reportes variados de la empresa.
												</ListItem>
											</ul>
										</NavigationMenuContent>
									</NavigationMenuItem>
								</NavigationMenuList>
							</NavigationMenu>
							<DropdownMenu>
								<DropdownMenuTrigger as-child>
									<Button variant="outline">
										{{ ($page.props.auth as any).user.name }}
									</Button>
								</DropdownMenuTrigger>
								<DropdownMenuContent class="mr-4 mt-1.5 w-56 sm:mr-8 xl:mr-6 2xl:mr-0">
									<DropdownMenuLabel>Mi Cuenta</DropdownMenuLabel>
									<Link :href="route('logout')" method="post" as="button" class="w-full">
										<DropdownMenuItem>
											<LogOut class="mr-2 h-4 w-4" />
											<span>Cerrar Sesión</span>
										</DropdownMenuItem>
									</Link>
								</DropdownMenuContent>
							</DropdownMenu>
						</div>
					</div>
				</div>
			</nav>

			<!-- Page Heading -->
			<header class="bg-white shadow" v-if="$slots.header">
				<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
					<slot name="header" />
				</div>
			</header>

			<!-- Page Content -->
			<main>
				<slot />
			</main>
		</div>
	</div>
	<Toaster />
</template>
