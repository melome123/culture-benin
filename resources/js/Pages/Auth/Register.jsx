// imports
import { Head, Link, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import Datepicker from 'react-datepicker';
import 'react-datepicker/dist/react-datepicker.css';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import React from 'react';

export default function Register() {
  const { data, setData, post, processing, errors, reset } = useForm({
    name: '',
    email: '',
    prenom: '',
    password: '',
    date_naissance: null,
    password_confirmation: '',
  });

  const submit = (e) => {
    e.preventDefault();
    post(route('register'), {
      onFinish: () => reset('password', 'password_confirmation'),
    });
  };

  return (
    <GuestLayout>
      <Head title="Register" />
      <form onSubmit={submit}>
        {/* name */}
        <InputLabel htmlFor="name" value="Nom" />
        <TextInput id="name" name="name" value={data.name} onChange={e => setData('name', e.target.value)} required />
        <InputError message={errors.name} />

        {/* prenom */}
        <InputLabel htmlFor="prenom" value="Prénom" />
        <TextInput id="prenom" name="prenom" value={data.prenom} onChange={e => setData('prenom', e.target.value)} required />
        <InputError message={errors.prenom} />

        {/* email */}
        <InputLabel htmlFor="email" value="Email" />
        <TextInput id="email" type="email" name="email" value={data.email} onChange={e => setData('email', e.target.value)} required />
        <InputError message={errors.email} />

        {/* password */}
        <InputLabel htmlFor="password" value="Mot de passe" />
        <TextInput id="password" type="password" name="password" value={data.password} onChange={e => setData('password', e.target.value)} required />
        <InputError message={errors.password} />

        {/* password confirmation */}
        <InputLabel htmlFor="password_confirmation" value="Confirmer le mot de passe" />
        <TextInput id="password_confirmation" type="password" name="password_confirmation" value={data.password_confirmation} onChange={e => setData('password_confirmation', e.target.value)} required />
        <InputError message={errors.password_confirmation} />

        {/* datepicker */}
        <div className="mb-3">
          <InputLabel htmlFor="date_naissance" value="Date de naissance" />
          <Datepicker
            selected={data.date_naissance}
            onChange={date => setData('date_naissance', date)}
            dateFormat="dd/MM/yyyy HH:mm"
            showTimeSelect
            timeFormat="HH:mm"
            timeIntervals={15}
            maxDate={new Date()}
            className={`form-control ${errors.date_naissance ? 'is-invalid' : ''}`}
          />
          <InputError message={errors.date_naissance} />
        </div>

        <div className="mt-4 flex items-center justify-end">
          <Link href={route('login')}>Already registered?</Link>
          <PrimaryButton className="ms-4" type="submit" disabled={processing}>Register</PrimaryButton>
        </div>
      </form>
    </GuestLayout>
  );
}
