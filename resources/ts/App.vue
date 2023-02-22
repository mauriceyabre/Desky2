<template>
    <RouterView />
</template>
<style>
	body,
	body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown):not(.modal-open),
	body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown):not(.modal-open):not(.sweetalert2-nopadding) {
		overflow-y: scroll !important;
		}

	body.modal-open {
		padding-right: 0 !important;
		}

	body.swal2-height-auto {
		height: 100% !important;
		}
</style>
