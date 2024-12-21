<?php

namespace App\Traits;

/**
 * Created by Sunil Thatal
 * 25-01-2021
 * https://github.com/thatal
 *
 * trait for livewire notification toastr take a look at appServiceProvider @customToastr Blade Directive
 * this dispatch work with toastr https://github.com/CodeSeven/toastr
 */
trait LivewireToastr {

    /**
     * @param string $message
     * @param string $type "success|error|warning|info"
     * @param array $configuration
     * @return void
     */

    public function dispatchToastr($message, $type = "success", $configuration = []) {
        $this->dispatchBrowserEvent("toastr", [
            "type"          => $type,
            "message"       => $message,
            "configuration" => $configuration,
        ]);
    }
}
