<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\User;
use App\Models\Topic;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Handlers\ImageUpload;
use App\Http\Requests\TopicRequest;
use Illuminate\Support\Facades\Auth;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index(User $user, Link $link)
    {
        $topics = Topic::with('user', 'category')->withOrder(request()->order)->paginate();
        $active_users = $user->getActiveUsers();

        $links = $link->getAllCached();


        return view('topics.index', compact('topics', 'active_users', 'links'));
    }

    public function show(Topic $topic, $slug = null)
    {
        // URL 矫正
        if ((! $topic->slug || $topic->slug !== $slug) &&
            ($topic->slug || $slug)
        ) {
            return redirect($topic->link(), 301);
        }

        return view('topics.show', compact('topic'));
    }

    public function create(Topic $topic)
    {
        $categories = Category::get();
        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    public function store(TopicRequest $request)
    {
        $topic = Auth::user()->topics()->create($request->all());
        // $topic = Topic::create($request->all());
        return redirect()->to($topic->link())->with('message', '成功创建主题！');
    }

    public function edit(Topic $topic)
    {
        $this->authorize('update', $topic);
        $categories = Category::get();
        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());

        return redirect()->to($topic->link())->with('message', '更新成功！');
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);
        $topic->delete();

        return redirect()->route('topics.index')->with('message', '成功删除！');
    }

    public function uploadImage(Request $request, ImageUpload $handler)
    {
        $data = [
            'success' => false,
            'msg' => '上传失败',
            'file_path' => ''
        ];

        if ($file = $request->upload_file) {
            $result = $handler->save($request->upload_file, 'topics', Auth::id());
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg'] = '上传成功';
                $data['success'] = true;
            }
        }

        return $data;
    }
}
