<?php

namespace App\Livewire\Account;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Livewire\Traits\WithSeo;

#[Layout('layouts.app')]
class AddressesPage extends Component
{
    use WithSeo;

    public $editing = null; // null, 'new', or index
    public $formName = '';
    public $formPhone = '';
    public $formAddress = '';
    public $formDistrict = '';
    public $formProvince = '';
    public $formPostalCode = '';
    public $formIsDefault = false;

    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    }

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function startNew()
    {
        $this->editing = 'new';
        $this->resetForm();

        $user = auth()->user();
        $this->formName = $user->name ?? '';
        $this->formPhone = $user->phone ?? '';
    }

    public function startEdit($index)
    {
        $addresses = auth()->user()->addresses ?? [];
        if (isset($addresses[$index])) {
            $addr = $addresses[$index];
            $this->editing = $index;
            $this->formName = $addr['name'] ?? '';
            $this->formPhone = $addr['phone'] ?? '';
            $this->formAddress = $addr['address'] ?? '';
            $this->formDistrict = $addr['district'] ?? '';
            $this->formProvince = $addr['province'] ?? '';
            $this->formPostalCode = $addr['postal_code'] ?? '';
            $this->formIsDefault = $addr['is_default'] ?? false;
        }
    }

    public function cancel()
    {
        $this->editing = null;
        $this->resetForm();
    }

    public function save()
    {
        if (!$this->formName || !$this->formPhone || !$this->formAddress || !$this->formProvince || !$this->formPostalCode) {
            $this->dispatch('toast', message: 'กรุณากรอกข้อมูลให้ครบ', type: 'error');
            return;
        }

        $user = auth()->user();
        $addresses = $user->addresses ?? [];

        $newAddr = [
            'name' => $this->formName,
            'phone' => $this->formPhone,
            'address' => $this->formAddress,
            'district' => $this->formDistrict,
            'province' => $this->formProvince,
            'postal_code' => $this->formPostalCode,
            'is_default' => $this->formIsDefault,
        ];

        if ($this->formIsDefault) {
            foreach ($addresses as &$a) {
                $a['is_default'] = false;
            }
            unset($a);
        }

        if ($this->editing === 'new') {
            $addresses[] = $newAddr;
            if (count($addresses) === 1) {
                $addresses[0]['is_default'] = true;
            }
        } else {
            $addresses[$this->editing] = $newAddr;
        }

        $user->addresses = $addresses;
        $user->save();

        $this->editing = null;
        $this->resetForm();
        $this->dispatch('toast', message: 'บันทึกที่อยู่สำเร็จ', type: 'success');
    }

    public function delete($index)
    {
        $user = auth()->user();
        $addresses = $user->addresses ?? [];

        if (isset($addresses[$index])) {
            array_splice($addresses, $index, 1);
            if (count($addresses) > 0 && !collect($addresses)->contains('is_default', true)) {
                $addresses[0]['is_default'] = true;
            }
            $user->addresses = $addresses;
            $user->save();
            $this->dispatch('toast', message: 'ลบที่อยู่สำเร็จ', type: 'success');
        }
    }

    public function setDefault($index)
    {
        $user = auth()->user();
        $addresses = $user->addresses ?? [];

        foreach ($addresses as $i => &$a) {
            $a['is_default'] = ($i === $index);
        }
        unset($a);

        $user->addresses = $addresses;
        $user->save();
        $this->dispatch('toast', message: 'ตั้งเป็นค่าเริ่มต้นแล้ว', type: 'success');
    }

    private function resetForm()
    {
        $this->formName = '';
        $this->formPhone = '';
        $this->formAddress = '';
        $this->formDistrict = '';
        $this->formProvince = '';
        $this->formPostalCode = '';
        $this->formIsDefault = false;
    }

    public function render()
    {
        $addresses = auth()->user()->addresses ?? [];

        $this->setSeo(
            title: 'ที่อยู่จัดส่ง — PGMF Shop',
            description: 'จัดการที่อยู่จัดส่งของคุณ',
        );

        return $this->renderWithSeo('livewire.account.addresses-page', [
            'addresses' => $addresses,
        ]);
    }
}
