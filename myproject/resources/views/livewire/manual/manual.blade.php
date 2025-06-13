@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl ">
    <!-- Header -->
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-purple-900 mb-2">Manual de Usuario Look Trendy</h1>
        <p class="text-gray-600">
            La aplicación web integral de gestión desarrollada para Tienda "Look Trendy" diseñada para simplificar y automatizar procesos clave.
        </p>
    </header>

    <!-- Table of Contents -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Contenido</h2>
        <ul class="space-y-2">
            <li><a href="#login" class="text-indigo-600 hover:underline">1. Acceso al sistema</a></li>
            <li><a href="#reset-password" class="text-indigo-600 hover:underline">2. Restablecer contraseña</a></li>
            <li><a href="#dashboard" class="text-indigo-600 hover:underline">3. Página principal del sistema</a></li>
            <li><a href="#new-purchase" class="text-indigo-600 hover:underline">4. Sección de Compras</a></li>
            <li><a href="#new-sale" class="text-indigo-600 hover:underline">5. Sección de Venta</a></li>
            <li><a href="#creditos" class="text-indigo-600 hover:underline">6. Seccion de Créditos</a></li>
            <li><a href="#abonos" class="text-indigo-600 hover:underline">7. Seccion de Abonos</a></li>
            <li><a href="#registros" class="text-indigo-600 hover:underline">8. Sección de Registros</a></li>
            <li><a href="#inventory" class="text-indigo-600 hover:underline">9. Sección de Inventario</a></li>
            <li><a href="#configuracion" class="text-indigo-600 hover:underline">10. Configuración y BackUps</a></li>
        </ul>
    </div>

    <!-- Login Section -->
    <section id="login" class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center mb-4">
                <div class="bg-pink-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-user text-pink-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-pink-800">Login</h3>
            </div>
        <p class="mb-4 text-gray-700">
            Para acceder al sistema, es necesario iniciar sesión utilizando las credenciales proporcionadas por el administrador del sistema.
        </p>
        
        <div class="bg-pink-50/50 p-4 rounded-lg mb-4 border border-pink-200">
            <h3 class="font-medium text-gray-800 mb-2">Pasos para iniciar sesión:</h3>
            <ol class="list-decimal pl-5 space-y-2">
                <li>Ingrese su correo electrónico en el campo <strong class="text-pink-700">"Email Address"</strong></li>
                <li>Ingrese su contraseña en el campo <strong class="text-pink-700">"Password"</strong></li>
                <li>Haga clic en el botón <strong class="text-pink-700">"Iniciar Sesión"</strong></li>
            </ol>
        </div>
        
        <p class="text-gray-700 mb-4">
            Asegúrese de introducir correctamente la información requerida para evitar errores de inicio de sesión.
        </p>
        
        <div class="flex justify-center my-6">
            <div class="border rounded-lg p-2 bg-gray-50 max-w-md">
                <img src="/images/manual/login.png" alt="Pantalla de inicio de sesión" class="rounded">
                <p class="text-center text-sm text-gray-500 mt-2">Vista de inicio de sesión</p>
            </div>
        </div>
        
        <p class="text-gray-700">
            La interfaz de inicio de sesión cuenta con un enlace para <a href="#reset-password" class="text-indigo-600 hover:underline">restablecer contraseña</a> en caso de olvido.
        </p>
    </section>

    <!-- Reset Password Section -->
    <section id="reset-password" class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center mb-4">
                <div class="bg-pink-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-lock text-pink-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-pink-800">Restablecer Contraseña</h3>
            </div>
        <p class="mb-4 text-gray-700">
            La interfaz de restablecimiento de contraseña permite a los usuarios recuperar el acceso al sistema en caso de que hayan olvidado su contraseña actual.
        </p>
        
        <div class="flex flex-col md:flex-row gap-6 mb-6">
            <div class="flex-1">
                <div class="border rounded-lg p-2 bg-gray-50 h-full">
                    <img src="/images/manual/restablecer.png" alt="Olvidé mi contraseña" class="rounded object-cover h-80 w-full">
                    <p class="text-center text-sm text-gray-500 mt-2">Ingrese su dirección de correo electrónico</p>
                </div>
            </div>
            <div class="flex-1">
                <div class="border rounded-lg p-2 bg-gray-50 h-full">
                    <img src="/images/manual/correo.png" alt="Restablecer contraseña" class="rounded object-cover h-80 w-full">
                    <p class="text-center text-sm text-gray-500 mt-2">Enlace enviado al correo para restablecer contraseña</p>
                </div>
            </div>
        </div>

        
        <div class="bg-pink-50/50 p-4 rounded-lg mb-4 border border-pink-200">
            <h3 class="font-medium text-gray-800 mb-2">Proceso para restablecer contraseña:</h3>
            <ol class="list-decimal pl-5 space-y-2">
                <li>Haga clic en el enlace <strong class="text-pink-700">"¿Olvidaste tu contraseña?"</strong> en la vista del login</li>
                <li>Ingrese su dirección de correo electrónico en el campo correspondiente</li>
                <li>Haga clic en el botón <strong class="text-pink-700">"Enviar enlace"</strong></li>
                <li>Revise su correo electrónico y haga clic en el botón <strong class="text-pink-700">"Crear nueva contraseña"</strong></li>
                <li>Ingrese su nueva contraseña en los campos indicados</li>
                <li>Haga clic en el botón <strong class="text-pink-700">"Restablecer contraseña"</strong></li>
            </ol>
        </div>
        
        <div class="flex justify-center my-6">
            <div class="border rounded-lg p-2 bg-gray-50 max-w-xs">
                <img src="/images/manual/nueva-contraseña.png" alt="Nueva contraseña" class="rounded">
                <p class="text-center text-sm text-gray-500 mt-2">Crear nueva contraseña</p>
            </div>
        </div>
    </section>

    <!-- Dashboard Section -->
    <section id="dashboard" class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center mb-4">
                <div class="bg-purple-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-home text-purple-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-purple-900">Página de Inicio</h3>
            </div>
        <p class="mb-4 text-gray-700">
            Una vez que haya iniciado sesión con éxito, se le dirigirá a la interfaz principal del sistema, desde donde podrá acceder a todas las funciones y módulos disponibles según su nivel de permisos asignado.
        </p>
        
        <div class="flex justify-center my-6">
            <div class="border rounded-lg p-2 bg-gray-50 max-w-4xl">
                <img src="/images/manual/inicio.png" alt="Página principal" class="rounded">
                <p class="text-center text-sm text-gray-500 mt-2">Interfaz principal del sistema</p>
            </div>
        </div>
        
        <div class="space-y-4">
            <div class="p-4 rounded-lg mb-4 border border-purple-200">
                <h3 class="font-medium text-lg text-purple-800 mb-2">Sección de Inicio</h3>
                <p class="text-gray-700">
                    Muestra una interfaz que da la bienvenida al usuario, incluyendo su nombre y la fecha actual. Presenta un resumen de los créditos otorgados en periodos específicos y enlaces directos a los módulos más utilizados.
                </p>
            </div>
            
            
        </div>
    </section>

    <!-- New Purchase Section -->
    <section id="new-purchase" class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center mb-4">
                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-shopping-cart text-blue-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-blue-800">Sección de Compras</h3>
            </div>
        <p class="text-gray-700">
                    Al desplegar el menú <strong class="text-blue-800">Compras</strong>, se accede al <strong class="text-blue-800">Dashboard</strong> de compras, donde se presentan los datos registrados en forma de visualizaciones para un análisis más claro.
                </p>
                <p class="text-gray-700 mt-2">
                    La opción <strong class="text-blue-800">Nueva Compra</strong> permite realizar compras a los proveedores, registrando la información necesaria para el control de inventario.
        </p><br>

        <h3 class="font-medium text-lg text-blue-800 mb-2 border-t pt-4">Dashboard de compras</h3>
        <p class="mb-4 text-gray-700">
            Aqui se muestra el dashboard que muestra los datos de las compras con filtros para acceder a la información rapidamente
        </p>
        
        <div class="flex justify-center my-6">
            <div class="border rounded-lg p-2 bg-gray-50 max-w-4xl">
                <img src="/images/manual/d-compra.png" alt="Nueva compra" class="rounded">
                <p class="text-center text-sm text-gray-500 mt-2">Dashboard de compras</p>
            </div>
        </div>

        <h3 class="font-medium text-lg text-blue-800 mb-2 border-t pt-4">Nueva Compra</h3>
        <p class="mb-4 text-gray-700">
            En esta sección se realizan las compras a los proveedores, registrando la información necesaria para el control de inventario y seguimiento de adquisiciones.
        </p>
        
        <div class="flex justify-center my-6">
            <div class="border rounded-lg p-2 bg-gray-50 max-w-4xl">
                <img src="/images/manual/nueva-compra.png" alt="Nueva compra" class="rounded">
                <p class="text-center text-sm text-gray-500 mt-2">Interfaz de nueva compra</p>
            </div>
        </div>

        <p class="mb-4 text-gray-700">
            En la parte superior del formulario se muestra el nombre del usuario que está registrando la compra, el proveedor que es seleccionado y la fecha de la transacción
        </p>

        <p class="mb-4 text-gray-700">
            Debajo, se encuentra la sección <strong class="text-blue-800">Productos</strong>, donde se pueden agregar los productos comprados haciendo clic en el botón <strong class="text-blue-800">“Agregar producto”</strong>, se abrirá una ventana donde podrá seleccionar los productos que desea comprar
        </p>

        <div class="flex justify-center my-6">
            <div class="border rounded-lg p-2 bg-gray-50 max-w-4xl">
                <img src="/images/manual/agregar-producto-compra.png" alt="Nueva compra" class="rounded">
                <p class="text-center text-sm text-gray-500 mt-2">Interfaz de nueva compra</p>
            </div>
        </div>

        <div class="bg-blue-50/50 p-4 rounded-lg border border-blue-100">
            <h3 class="font-medium text-lg text-blue-800 mb-2">Agregar Productos</h3>
            <ul class="list-disc pl-5 space-y-2">
                <li>En la parte superior de esta ventana se encuentra una barra de búsqueda que permite localizar los productos de forma rápida</li>
                <li>En el campo <strong class="text-blue-800">Cantidad</strong>, debe ingresar la cantidad a comprar</li>
                <li>En el campo <strong class="text-blue-800">Precio unitario</strong>, debe especificar el costo por unidad del producto</li>
                <li>Haga clic en <strong class="text-blue-800">“Agregar”</strong></li>
                <li>Seleccione el <strong class="text-blue-800">método de pago</strong> correspondiente</li>
                <li>Una vez que haya indicado todos los productos a comprar, haga clic en <strong class="text-blue-800">“Finalizar Selección”</strong> </li>
            </ul>
        </div><br>
        
        <p class="mb-4 text-gray-700">
            En la parte inferior, se muestra el <strong class="text-blue-800">Subtotal</strong> y <strong class="text-blue-800">Total con IVA</strong> de la compra. Para finalizar el proceso, debe hacer clic en el botón <strong class="text-blue-800">"Guardar Compra"</strong> 
        </p>
    </section>

    <!-- Ventas -->
    <section id="new-sale" class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center mb-4">
                <div class="bg-pink-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-shopping-basket text-pink-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-pink-900">Sección de Ventas</h3>
            </div>
        <p class="text-gray-700">
                    Al desplegar el menú <strong class="text-pink-700">Ventas</strong>, se accede al <strong class="text-pink-700">Dashboard</strong> de Ventas, donde se presentan los datos registrados en forma de visualizaciones para un análisis más claro.
                </p>
                <p class="text-gray-700 mt-2">
                    La opción <strong class="text-pink-700">Nueva Venta</strong> permite registrar las ventas al contado con la información necesaria tanto de los clientes como de los productos.
        </p><br>

        <h3 class="font-medium text-lg text-pink-800 mb-2 border-t pt-4">Dashboard de Ventas</h3>
        <p class="mb-4 text-gray-700">
            En esta sección se muestra el dashboard que muestra los datos de las Ventas con filtros para acceder a la información rapidamente
        </p>
        
        <div class="flex justify-center my-6">
            <div class="border rounded-lg p-2 bg-gray-50 max-w-4xl">
                <img src="/images/manual/d-compra.png" alt="Nueva compra" class="rounded">
                <p class="text-center text-sm text-gray-500 mt-2">Dashboard de Ventas</p>
            </div>
        </div>

        <h3 class="font-medium text-lg text-pink-800 mb-2 border-t pt-4">Nueva Venta</h3>
        <p class="mb-4 text-gray-700">
            En esta sección se realizan las venta al contado, registrando la información necesaria para el control de inventario.
        </p>
        
        <div class="flex justify-center my-6">
            <div class="border rounded-lg p-2 bg-gray-50 max-w-4xl">
                <img src="/images/manual/venta.png" alt="Nueva Venta" class="rounded">
                <p class="text-center text-sm text-gray-500 mt-2">Interfaz de nueva Venta</p>
            </div>
        </div>

        <p class="mb-4 text-gray-700">
            En la parte superior del formulario se muestra el nombre del usuario que está registrando la venta, el cliente que debe ser seleccionado de acuerdo a los clientes que estan registrados y la fecha de la venta.
        </p>

        <p class="mb-4 text-gray-700">
            Debajo, se encuentra la sección <strong class="text-pink-700">Productos</strong>, donde se pueden agregar los productos comprados haciendo clic en el botón <strong class="text-pink-700">“Agregar producto”</strong>, ”, se abrirá una ventana donde podrá seleccionar los productos que desea comprar
        </p>

        <div class="flex justify-center my-6">
            <div class="border rounded-lg p-2 bg-gray-50 max-w-4xl">
                <img src="/images/manual/agregar-producto-venta.png" alt="Nueva compra" class="rounded">
                <p class="text-center text-sm text-gray-500 mt-2">Interfaz de nueva venta</p>
            </div>
        </div>

        <div class="bg-pink-50/50 p-4 rounded-lg border border-pink-100">
            <h3 class="font-medium text-lg text-pink-800 mb-2">Agregar Productos</h3>
            <ul class="list-disc pl-5 space-y-2">
                <li>En la parte superior de esta ventana se encuentra una barra de búsqueda que permite localizar los productos de forma rápida</li>
                <li>En el campo <strong class="text-pink-700">Cantidad</strong>, debe ingresar la cantidad solicitada por el cliente</li>
                <li>Haga clic en <strong class="text-pink-700">“Agregar”</strong> para agregar el producto a la tabla</li>
            </ul>
        </div><br>
        <p class="mb-4 text-gray-700">
            En la parte inferior, se muestra el Subtotal de la venta y el total con IVA. Una vez que se hayan seleccionado todolos los productos se <strong class="text-pink-700">selecciona el método de pago </strong> y se guarda la venta
        </p>
        
    </section>

    <!-- Créditos -->
<section id="creditos" class="bg-white rounded-lg shadow-md p-6 mb-8">
    <div class="flex items-center mb-4">
        <div class="bg-pink-100 p-2 rounded-lg mr-3">
            <i class="fas fa-credit-card text-pink-600"></i>
        </div>
        <h3 class="text-xl font-semibold text-pink-900">Sección de Créditos</h3>
    </div>
     <h3 class="font-medium text-lg text-pink-800 mb-2 border-t pt-4">Dashboard de Créditos</h3>
    <p class="mb-4 text-gray-700">
        Esta sección presenta un panel con los datos de las ventas a crédito, donde se pueden aplicar filtros para una búsqueda rápida.
    </p>

    <div class="flex justify-center my-6">
        <div class="border rounded-lg p-2 bg-gray-50 max-w-4xl">
            <img src="/mnt/data/cb6df330-f222-4a15-9dad-d7be369415ae.png" alt="Dashboard de Créditos" class="rounded">
            <p class="text-center text-sm text-gray-500 mt-2">Dashboard de Créditos</p>
        </div>
    </div>
    <p class="text-gray-700">
        Al desplegar el menú <strong class="text-pink-700">Créditos</strong>, se accede al <strong class="text-pink-700">Dashboard</strong> de ventas a crédito, donde se presentan los datos registrados en forma de visualizaciones para un análisis más claro.
    </p>
     <h3 class="font-medium text-lg text-pink-800 mb-2 border-t pt-4">Nuevo Crédito</h3>
    <p class="text-gray-700 mt-2">
        La opción <strong class="text-pink-700">Nuevo Crédito</strong> permite registrar ventas con financiamiento, ingresando la información necesaria tanto del cliente como de los productos adquiridos.
    </p><br>
 <div class="flex justify-center my-6">
            <div class="border rounded-lg p-2 bg-gray-50 max-w-4xl">
                <img src="/images/manual/credito.png" alt="Nueva Venta" class="rounded">
                <p class="text-center text-sm text-gray-500 mt-2">Interfaz de nueva Venta</p>
            </div>
        </div>
    <p class="mb-4 text-gray-700">
        Desde esta sección puedes realizar ventas a crédito. Se debe registrar al cliente, los productos, y condiciones del crédito como el plazo y forma de pago.
    </p>

    <p class="mb-4 text-gray-700">
        En la parte superior del formulario se muestra el nombre del usuario que está registrando la venta, el cliente seleccionado y la fecha de la operación.
    </p>

    <p class="mb-4 text-gray-700">
        Luego, en la sección <strong class="text-pink-700">Productos</strong>, puedes agregar los productos deseados haciendo clic en <strong class="text-pink-700">“Agregar producto”</strong>. Se abrirá una ventana emergente para seleccionar los productos.
    </p>

    <div class="flex justify-center my-6">
        <div class="border rounded-lg p-2 bg-gray-50 max-w-4xl">
            <img src="/images/manual/productoCredito.png" alt="Agregar Producto" class="rounded">
            <p class="text-center text-sm text-gray-500 mt-2">Agregar productos al crédito</p>
        </div>
    </div>

    <div class="bg-pink-50/50 p-4 rounded-lg border border-pink-100">
        <h3 class="font-medium text-lg text-pink-800 mb-2">Agregar Productos</h3>
        <ul class="list-disc pl-5 space-y-2">
            <li>Utiliza la barra de búsqueda para localizar productos rápidamente.</li>
            <li>En el campo <strong class="text-pink-700">Cantidad</strong>, introduce la cantidad solicitada.</li>
            <li>Haz clic en <strong class="text-pink-700">“Agregar”</strong> para incluir el producto en la tabla.</li>
        </ul>
    </div><br>

    <p class="mb-4 text-gray-700">
        En la parte inferior se muestra el subtotal y total con IVA. Finalmente, se selecciona el <strong class="text-pink-700">método de pago</strong> y se guarda la venta a crédito.
    </p>
</section>

<!-- Abonos Section -->
<section id="abonos" class="bg-white rounded-lg shadow-md p-6 mb-8">
  <div class="flex items-center mb-4">
    <div class="bg-emerald-100 p-2 rounded-lg mr-3">
      <i class="fas fa-money-bill-wave text-emerald-600"></i>
    </div>
    <h3 class="text-xl font-semibold text-emerald-900">Gestión de Abonos</h3>
  </div>
  <p class="text-gray-700">
    Esta sección permite llevar el control de los pagos realizados por los clientes a sus créditos activos. La interfaz muestra estadísticas rápidas, una lista de abonos recientes, y permite registrar nuevos pagos.
  </p>
  <p class="text-gray-700 mt-6">
    La tabla inferior muestra el historial de abonos registrados. Cada fila incluye el cliente, número de crédito, fecha del abono y monto. Además, puedes generar un comprobante PDF desde la columna de acciones.
  </p>

  <div class="flex justify-center my-6">
    <div class="border rounded-lg p-2 bg-gray-50 max-w-4xl">
      <img src="/images/manual/vistaAbono.png" alt="Vista de Abonos" class="rounded">
      <p class="text-center text-sm text-gray-500 mt-2">Vista de abonos registrados</p>
    </div>
  </div>
  <div class="flex items-center mb-4">
    <div class="bg-emerald-100 p-2 rounded-lg mr-3">
      <i class="fas fa-plus-circle text-emerald-600"></i>
    </div>
    <h3 class="text-xl font-semibold text-emerald-900">Registrar Nuevo Abono</h3>
  </div>

  <p class="text-gray-700">
    Al hacer clic en <strong class="text-emerald-700">“Nuevo Abono”</strong>, se abre una ventana donde puedes registrar un nuevo pago para un cliente con crédito activo. Se debe seleccionar el crédito correspondiente e ingresar el monto, fecha y tipo de pago.
  </p>

  <ul class="list-disc pl-5 mt-4 text-gray-700 space-y-2">
    <li>Busca el crédito por nombre del cliente o número de crédito.</li>
    <li>Verifica el saldo pendiente antes de ingresar el monto.</li>
    <li>Elige la fecha del abono y el tipo de pago utilizado.</li>
    <li>Haz clic en <strong class="text-emerald-700">“Guardar”</strong> para registrar el abono.</li>
  </ul>
  <div class="flex justify-center my-6">
    <div class="border rounded-lg p-2 bg-gray-50 max-w-4xl">
      <img src="/images/manual/registrarAbono.png" alt="Formulario de Nuevo Abono" class="rounded">
      <p class="text-center text-sm text-gray-500 mt-2">Formulario para registrar un nuevo abono</p>
    </div>
  </div>

  <p class="text-gray-700">
    Una vez guardado, el sistema actualizará automáticamente el monto abonado en el crédito y reducirá el saldo pendiente.
  </p>
</section>

<!-- Registros -->
<section id="registros" class="bg-white rounded-xl shadow-md p-6 mb-8 border border-purple-100">
    
    <div class="flex items-center mb-4 pb-2 border-b border-purple-100">
        <div class="bg-yellow-100 p-2 rounded-lg mr-3">
            <i class="fas fa-address-book text-yellow-600"></i>
        </div>
        <h2 class="text-2xl font-semibold text-yellow-800">Gestión de Registros</h2>
    </div>

    <div class="bg-yellow-50 p-4 rounded-lg mb-6 border border-yellow-100">
        <p class="text-yellow-800">
            <i class="fas fa-info-circle mr-1 text-yellow-600"></i> Todas las interfaces de registro (usuarios, clientes, proveedores) comparten las mismas funciones básicas: 
            <span class="font-medium">crear, editar, buscar y eliminar</span>. Primero se muestra una tabla con los registros existentes y una barra de búsqueda.
        </p>
    </div>

    <div class="flex justify-center my-8">
        <div class="border-2 border-yellow-100 rounded-lg p-1 bg-white shadow-sm max-w-2xl">
            <img src="/images/manual/registro.png" alt="Vista principal de registros" class="rounded-lg">
            <p class="text-center text-sm text-gray-500 mt-2">Interfaz principal de gestión de registros</p>
        </div>
    </div>

    <div class="mb-6" x-data="{ activeTab: 'agregar' }"> 
        <div class="flex overflow-x-auto pb-2">
            <button @click="activeTab = 'agregar'" 
                    :class="{'bg-yellow-500 text-white': activeTab === 'agregar', 'bg-white text-yellow-800': activeTab !== 'agregar'}" 
                    class="px-6 py-3 rounded-t-lg font-medium mr-2 border border-yellow-200 hover:bg-yellow-100 transition-colors flex items-center">
                <i class="fas fa-plus-circle mr-2"></i> Agregar
            </button>
            <button @click="activeTab = 'editar'" 
                    :class="{'bg-yellow-500 text-white': activeTab === 'editar', 'bg-white text-yellow-800': activeTab !== 'editar'}" 
                    class="px-6 py-3 rounded-t-lg font-medium mr-2 border border-yellow-200 hover:bg-yellow-100 transition-colors flex items-center">
                <i class="fas fa-edit mr-2"></i> Editar
            </button>
            <button @click="activeTab = 'eliminar'" 
                    :class="{'bg-yellow-500 text-white': activeTab === 'eliminar', 'bg-white text-yellow-800': activeTab !== 'eliminar'}" 
                    class="px-6 py-3 rounded-t-lg font-medium mr-2 border border-yellow-200 hover:bg-yellow-100 transition-colors flex items-center">
                <i class="fas fa-trash-alt mr-2"></i> Eliminar
            </button>
        </div>

        <div class="border border-t-0 rounded-b-lg border-gray-200 p-6 bg-white">
            <!-- Agregar -->
            <div x-show="activeTab === 'agregar'" class="grid md:grid-cols-2 gap-8 items-start">
                <div>
                    <h3 class="text-xl font-semibold text-yellow-700 mb-4 flex items-center">
                        Agregar Nuevo Registro
                    </h3>
                    <div class="space-y-4">
                        <ol class="list-decimal pl-5 space-y-3 text-gray-700">
                            <li>Haga clic en <strong class="text-yellow-600">"Nuevo Usuario"</strong> (el nombre varía según el registro)</li>
                            <li>Complete todos los campos obligatorios (*)</li>
                            <li>Haga clic en <strong class="text-yellow-600">"Registrar"</strong> para guardar</li>
                            <li>Use <strong class="text-yellow-600">"Cancelar"</strong> si desea abortar la acción</li>
                        </ol>
                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200 mt-4">
                            <p class="text-yellow-800 flex items-start">
                                <i class="fas fa-info-circle text-yellow-500 mr-2 mt-1"></i>
                                <span>El sistema valida automáticamente los datos para evitar información duplicada, incompleta o con formato incorrecto.</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="border-2 border-gray-100 rounded-lg p-1 bg-white shadow-sm">
                        <img src="/images/manual/registro-agregar.png" alt="Formulario para agregar registro" class="rounded-lg w-full max-w-md">
                        <p class="text-center text-sm text-gray-500 mt-2">Formulario de registro</p>
                    </div>
                </div>
            </div>

            <!-- Editar -->
            <div x-show="activeTab === 'editar'" x-cloak class="grid md:grid-cols-2 gap-8 items-start">
                <div>
                    <h3 class="text-xl font-semibold text-yellow-700 mb-4 flex items-center">
                        Editar Registros
                    </h3>
                    <div class="space-y-4">
                        <ol class="list-decimal pl-5 space-y-3 text-gray-700">
                            <li>En la tabla, haga clic en <i class="fas fa-edit text-yellow-500 mx-1"></i> del registro a modificar</li>
                            <li>Actualice los campos necesarios</li>
                            <li>Confirme con <strong class="text-yellow-600">"Actualizar"</strong></li>
                            <li>Use <strong class="text-yellow-600">"Cancelar"</strong> para descartar cambios</li>
                        </ol>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="border-2 border-gray-100 rounded-lg p-1 bg-white shadow-sm">
                        <img src="/images/manual/registro-editar.png" alt="Formulario de edición" class="rounded-lg w-full max-w-md">
                        <p class="text-center text-sm text-gray-500 mt-2">Edición de registros</p>
                    </div>
                </div>
            </div>

            <!-- Eliminar -->
            <div x-show="activeTab === 'eliminar'" x-cloak class="grid md:grid-cols-2 gap-8 items-start">
                <div>
                    <h3 class="text-xl font-semibold text-yellow-700 mb-4 flex items-center">
                        Eliminar Registros
                    </h3>
                    <div class="space-y-4">
                        <ol class="list-decimal pl-5 space-y-3 text-gray-700">
                            <li>En la tabla, haga clic en <i class="fas fa-trash-alt text-yellow-500 mx-1"></i> del registro a eliminar</li>
                            <li>Confirme la acción en el diálogo emergente</li>
                            <li>Use <strong class="text-yellow-600">"Cancelar"</strong> para abortar la eliminación</li>
                        </ol>
                        <div class="bg-red-50 p-4 rounded-lg border border-red-200 mt-4">
                            <p class="text-red-800 flex items-start">
                                <i class="fas fa-exclamation-triangle text-red-500 mr-2 mt-1"></i>
                                <span><strong>Advertencia:</strong> Esta acción es irreversible. Los datos eliminados no pueden recuperarse.</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="border-2 border-gray-100 rounded-lg p-1 bg-white shadow-sm">
                        <img src="/images/manual/registro-eliminar.png" alt="Diálogo de confirmación" class="rounded-lg w-full max-w-md">
                        <p class="text-center text-sm text-gray-500 mt-2">Confirmación de eliminación</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Inventory Section -->
    <section id="inventory" class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center mb-4 pb-2 border-b boder-red-100">
            <div class="bg-red-100 p-2 rounded-lg mr-3">
                <i class="fas fa-clipboard-list text-red-600"></i>
            </div>
            <h2 class="text-2xl font-semibold text-red-800">Gestión de Inventario</h2>
        </div>

        <div class="p-4 rounded-lg mb-6 border border-gray-100">
        <p class="text-gray-700">
            Al desplegar el menú <strong class="text-red-700">Inventario</strong>, se accede a las interfaces necesarias para gestionar la información relacionada con los productos, las categorías y el stock actual.
        </p>
    </div>

       <h3 class="font-medium text-lg text-red-800 mt-6 mb-2 border-t pt-4">Panel principal</h3>
    
    <p class="text-gray-700">
        Esta sección muestra el inventario actual de productos, permitiendo revisar el stock disponible, detectar productos con bajo inventario y exportar reportes.
    </p><br>

    <p class="mb-4 text-gray-700">
        El panel incluye una barra de búsqueda para filtrar productos por nombre, y un selector de fecha para visualizar los movimientos de inventario en un día específico.
    </p>

    <div class="flex justify-center my-6">
        <div class="border rounded-lg p-2 bg-gray-50 max-w-4xl">
            <img src="/images/manual/inventario.png" alt="Módulo de inventario" class="rounded">
            <p class="text-center text-sm text-gray-500 mt-2">Módulo de inventario</p>
        </div>
    </div>

    <p class="mb-4 text-gray-700">
        Debajo del filtro se encuentra un resumen de los movimientos del día, con indicadores como <strong class="text-red-700">Entradas</strong> y <strong class="text-red-700">Salidas</strong> del día, y <strong class="text-red-700">Total de Entradas</strong> y <strong class="text-red-700">Total de Salidas</strong> en general, facilitando el seguimiento de los movimientos del inventario
    </p>

    <h3 class="font-medium text-lg text-red-800 mt-6 mb-2 border-t pt-4">Tabla de productos</h3>
    <p class="mb-4 text-gray-700">
        En la parte inferior se muestra una tabla con todos los productos registrados. Incluye información clave como nombre del producto, stock actual, stock mínimo y estado.
    </p>

    <div class="bg-blue-50 p-4 rounded-lg border border-blue100">
        <div class="flex items-center mb-4">
                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-info-circle text-blue-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-blue-800">Indicadores Útiles</h3>
            </div>
        <ul class="list-disc pl-5 space-y-2 text-gray-700">
            <li>Los productos con stock por debajo del mínimo son marcados como <strong class="text-blue-700">Bajo Stock</strong></li>
            <li>Un contador muestra cuántos productos presentan bajo inventario</li>
            <li>Puede exportar el inventario completo en formato <strong class="text-blue-700">Excel</strong> o <strong class="text-blue-700">PDF</strong> usando los botones ubicados en la parte superior</li>
        </ul>
    </div><br>

    <p class="mb-4 text-gray-700">
        Esta funcionalidad permite mantener un control claro del inventario, ayudando a tomar decisiones rápidas sobre las adquisiciones del negocio
    </p><br>

        <!-- Productos -->
        <div class="mb-8">
            <div class="flex items-center mb-4 pb-2 border-b border-red-100">
        <div class="bg-red-100 p-2 rounded-lg mr-3">
            <i class="fas fa-boxes text-red-600"></i>
        </div>
        <h2 class="text-2xl font-semibold text-red-800">Gestión de Productos</h2>
    </div>
            
            <div class="bg-yellow-50 p-4 rounded-lg mb-6 border border-yellow-100">
        <p class="text-yellow-800">
            <i class="fas fa-info-circle mr-1 text-yellow-600"></i> El proceso para <strong>agregar, editar y eliminar</strong> productos 
            se realiza de la misma forma que con los otros registros. Para ver los detalles completos de cada acción, consulte la sección de 
            <a href="#registros" class="text-yellow-600 hover:underline font-medium">Gestión de Registros</a>.
        </p>
    </div>

    <div class="bg-red-50 p-4 rounded-lg mb-6 border border-red-200">
        <h4 class="font-medium text-red-700 mb-2 flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i> Importante sobre el stock:
        </h4>
        <p class="text-red-700">
            Registrar un producto aquí <strong>no genera stock automáticamente</strong>. Para que un producto tenga existencia disponible, 
            debe realizarse una compra desde el módulo correspondiente. Este registro solo establece la ficha técnica del producto.
        </p>
    </div>

    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
            Tabla de Productos
        </h3>
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-700 mb-4">
                    La tabla muestra todos los productos registrados con sus datos básicos. Desde aquí puede:
                </p>
                <ul class="list-disc pl-5 space-y-2 text-gray-700">
                    <li>Buscar productos con la barra de búsqueda superior</li>
                    <li>Agregar nuevos productos (botón superior derecho)</li>
                    <li>Editar o eliminar productos existentes</li>
                </ul>
            </div>
            <div class="flex justify-center">
                <div class="border rounded-lg p-2 bg-gray-50">
                    <img src="/images/manual/productos.png" alt="Tabla de productos" class="rounded max-w-full h-auto">
                    <p class="text-center text-sm text-gray-500 mt-2">Vista de la tabla de productos</p>
                </div>
            </div>
        </div>
    </div>

        <!-- Categorias -->
    <div class="flex items-center mb-4 pb-2 border-b border-yellow-100">
        <div class="bg-yellow-100 p-2 rounded-lg mr-3">
            <i class="fas fa-tags text-yellow-600"></i>
        </div>
        <h2 class="text-2xl font-semibold text-yellow-800">Gestión de Categorías</h2>
    </div>

    <div class="bg-yellow-50 p-4 rounded-lg mb-6 border border-yellow-100">
        <p class="text-yellow-800">
            <i class="fas fa-info-circle mr-1 text-yellow-600"></i> Al igual que con los productos, el proceso para <strong>agregar, editar y eliminar</strong> categorías
            sigue el mismo flujo que otros registros. Revise los detalles en la sección de 
            <a href="#registros" class="text-yellow-600 hover:underline font-medium">Gestión de Registros</a>.
        </p>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Organización de productos</h3>
            <p class="text-gray-700 mb-4">
                Las categorías permiten clasificar los productos para un mejor control del inventario y una búsqueda más eficiente.
            </p>
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <p class="text-blue-700">
                    <i class="fas fa-lightbulb mr-1"></i> <strong>Consejo:</strong> Establezca categorías claras y consistentes para facilitar la gestión del inventario.
                </p>
            </div>
        </div>
        <div class="flex justify-center">
            <div class="border rounded-lg p-2 bg-gray-50">
                <img src="/images/manual/categorias.png" alt="Gestión de categorías" class="rounded max-w-full h-auto">
                <p class="text-center text-sm text-gray-500 mt-2">Interfaz de categorías</p>
            </div>
        </div>
    </div>
</div>
    </section>

    <!-- Módulo de Configuración-->
<section id="configuracion" class="bg-white rounded-lg shadow-sm p-6 mb-8 border border-blue-100">

    <div class="flex items-center mb-4 pb-2 border-b border-blue-100">
        <div class="bg-blue-100 p-2 rounded-lg mr-3">
            <i class="fas fa-database text-blue-600"></i>
        </div>
        <h2 class="text-2xl font-semibold text-blue-800">Backup y Restauración</h2>
    </div>
    <div class="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-200">
        <p class="text-blue-800">
            <i class="fas fa-info-circle mr-1 text-blue-500"></i> Este módulo permite <strong>crear copias de seguridad</strong> de toda la información del sistema y <strong>restaurarlas</strong> cuando sea necesario.
        </p>
    </div>

    <!-- Proceso completo -->
    <div class="grid md:grid-cols-2 gap-8 mb-6">
        <div>
            <h3 class="text-xl font-semibold text-blue-700 mb-3 flex items-center">
                Pasos para Backup
            </h3>
            <ol class="list-decimal pl-5 space-y-3 text-gray-700">
                <li>Haga clic en <strong class="text-blue-600">"Crear Backup"</strong></li>
                <li>El sistema generará un archivo con fecha automática</li>
                <li>Aparecerá en la lista <strong>"Backups disponibles"</strong></li>
                <li>Puede <strong>descargarlo</strong> <i class="fas fa-download text-blue-500"></i> o <strong>eliminarlo</strong> <i class="fas fa-trash-alt text-blue-500"></i></li>
            </ol>
        </div>
        <div class="flex justify-center">
            <div class="border-2 border-blue-100 rounded-lg p-1 bg-white">
                <img src="/images/manual/crear-backup.png" alt="Crear backup" class="rounded-lg w-full max-w-md">
                <p class="text-center text-sm text-gray-500 mt-2">Botón para crear backup</p>
            </div>
        </div>
    </div>

    <!-- Restauración -->
    <div class="grid md:grid-cols-2 gap-8">
        <div>
            <h3 class="text-xl font-semibold text-blue-700 mb-3 flex items-center">
                Restaurar Backup
            </h3>
            <ol class="list-decimal pl-5 space-y-3 text-gray-700">
                <li>Haga clic en <strong class="text-blue-600">"Seleccionar archivo"</strong></li>
                <li>Elija el archivo de backup (.backup) desde su computadora</li>
                <li>Haga clic en <strong class="text-blue-600">"Restaurar Backup"</strong></li>
                <li>Confirme la acción en el diálogo emergente</li>
            </ol>
            
            <div class="bg-red-50 p-4 rounded-lg border border-red-200 mt-4">
                <p class="text-red-700 flex items-start">
                    <i class="fas fa-exclamation-triangle mr-2 mt-1 text-red-500"></i>
                    <span><strong>Precaución:</strong> La restauración sobrescribirá todos los datos actuales. Asegúrese de tener un backup reciente antes de restaurar.</span>
                </p>
            </div>
        </div>
        <div class="flex justify-center">
            <div class="border-2 border-blue-100 rounded-lg p-1 bg-white">
                <img src="/images/manual/restaurar.png" alt="Restaurar backup" class="rounded-lg w-full max-w-md">
                <p class="text-center text-sm text-gray-500 mt-2">Selección de archivo para restaurar</p>
            </div>
        </div>
    </div>

    <!-- Nota final -->
    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mt-6">
        <h4 class="font-medium text-blue-800 mb-2 flex items-center">
            <i class="fas fa-lightbulb mr-2 text-blue-600"></i> Recomendaciones:
        </h4>
        <ul class="list-disc pl-5 space-y-1 text-blue-700">
            <li>Realice backups regularmente (semanal o mensualmente)</li>
            <li>Guarde copias en diferentes ubicaciones (nube, disco externo)</li>
            <li>Verifique que los backups se crearon correctamente</li>
        </ul>
    </div>
</section>



<footer class="text-center text-gray-500 text-sm mt-12">
        <p>© {{ date('Y') }} Look Trendy. Todos los derechos reservados.</p>
        <p class="mt-1">Manual de usuario v1.0</p>
    </footer>
@endsection

