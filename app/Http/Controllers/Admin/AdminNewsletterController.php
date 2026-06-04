<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\NewsletterSubscriber;

class AdminNewsletterController extends Controller
{
    public function index()
    {
        $subscribers = NewsletterSubscriber::latest()->paginate(20);
        return view('admin.newsletter', compact('subscribers'));
    }

    public function destroy($id)
    {
        NewsletterSubscriber::findOrFail($id)->delete();
        return redirect()->route('admin.newsletter.index')
            ->with('success', 'Subscriber newsletter berhasil dihapus.');
    }
}