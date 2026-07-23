<?php

namespace App\Helpers;

class Toastr
{
    public function success(string $message): self
    {
        session()->flash('toastr', [
            'type' => 'success',
            'message' => $message,
        ]);

        return $this;
    }

    public function error(string $message): self
    {
        session()->flash('toastr', [
            'type' => 'error',
            'message' => $message,
        ]);

        return $this;
    }

    public function warning(string $message): self
    {
        session()->flash('toastr', [
            'type' => 'warning',
            'message' => $message,
        ]);

        return $this;
    }

    public function info(string $message): self
    {
        session()->flash('toastr', [
            'type' => 'info',
            'message' => $message,
        ]);

        return $this;
    }
}
