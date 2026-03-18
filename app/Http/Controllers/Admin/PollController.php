<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PollService;
use App\Repositories\PollRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class PollController extends Controller
{
    protected $pollService;
    protected $pollRepo;

    public function __construct(PollService $pollService, PollRepositoryInterface $pollRepo)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->pollService = $pollService;
        $this->pollRepo = $pollRepo;
    }

    public function index()
    {
        $polls = $this->pollRepo->listByAdmin(Auth::id());
        return view('admin.polls.index', compact('polls'));
    }

    public function create()
    {
        return view('admin.polls.create');
    }

    public function store(Request $r)
    {
        $r->validate(['question'=>'required','options'=>'required|array|min:2']);
        $poll = $this->pollService->createPollWithOptions(Auth::id(), $r->question, $r->options);
        return redirect()->route('admin.polls.index')->with('success','Poll created');
    }

    public function show($id)
    {
        $poll = $this->pollRepo->findByUuid($id);
        if(!$poll) abort(404);
        return view('admin.polls.show', compact('poll'));
    }
}