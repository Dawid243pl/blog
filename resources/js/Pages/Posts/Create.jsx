import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import { useForm } from "@inertiajs/react";
import { usePage } from "@inertiajs/react";
import PostForm from "@/Components/Posts/Form";

export default function CreatePost() {
    const { flash } = usePage().props;
    console.log("flash", flash);

    const { data, setData, post, processing, errors } = useForm({
        title: "",
        content: "",
    });

    function submit(e) {
        e.preventDefault();
        post(route("post.store"), {
            onSuccess: () =>
                setData({
                    title: "",
                    content: "",
                }),
        });
    }
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Add Post
                </h2>
            }
        >
            <Head title="Add Post" />
            <PostForm
                submit={submit}
                data={data}
                setData={setData}
                processing={processing}
                errors={errors}
                flash={flash}
                buttonText="Create"
            />
        </AuthenticatedLayout>
    );
}
