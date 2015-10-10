--
-- TOC entry 5 (OID 73130224)
-- Name: wl_users; Type: TABLE; Schema: public; Owner: A995847_fin
--

CREATE TABLE wl_users (
    id serial NOT NULL,
    last_name character varying NOT NULL,
    first_name character varying,
    user_id character varying NOT NULL,
    email_address character varying NOT NULL,
    "password" character varying NOT NULL,
    sec_question character varying NOT NULL,
    sec_ans character varying NOT NULL,
    intial_weight double precision NOT NULL
);


--
-- TOC entry 6 (OID 73130372)
-- Name: wl_week; Type: TABLE; Schema: public; Owner: A995847_fin
--

CREATE TABLE wl_week (
    id bigserial NOT NULL,
    wl_user_id integer NOT NULL,
    week_number integer NOT NULL,
    calorie_goal integer,
    activity_mins integer,
    start_date timestamp without time zone,
    end_date timestamp without time zone
);


--
-- TOC entry 7 (OID 73130390)
-- Name: wl_day; Type: TABLE; Schema: public; Owner: A995847_fin
--

CREATE TABLE wl_day (
    id bigserial NOT NULL,
    wl_week_id bigserial NOT NULL,
    day_name character varying NOT NULL,
    date date
);


--
-- TOC entry 8 (OID 73130403)
-- Name: wl_item_type; Type: TABLE; Schema: public; Owner: A995847_fin
--

CREATE TABLE wl_item_type (
    id bigserial NOT NULL,
    wl_day_id bigserial NOT NULL,
    description character varying NOT NULL,
    "type" integer,
    amount double precision,
    "time" timestamp without time zone
);


--
-- TOC entry 9 (OID 73131172)
-- Name: wl_weight_xfre; Type: TABLE; Schema: public; Owner: A995847_fin
--

CREATE TABLE wl_weight_xfre (
    id bigserial NOT NULL,
    wl_user_id bigserial NOT NULL,
    date timestamp without time zone NOT NULL,
    weight double precision NOT NULL
);


--
-- TOC entry 10 (OID 73130230)
-- Name: wl_users_pkey; Type: CONSTRAINT; Schema: public; Owner: A995847_fin
--

ALTER TABLE ONLY wl_users
    ADD CONSTRAINT wl_users_pkey PRIMARY KEY (id);


--
-- TOC entry 11 (OID 73130232)
-- Name: wl_users_user_id_key; Type: CONSTRAINT; Schema: public; Owner: A995847_fin
--

ALTER TABLE ONLY wl_users
    ADD CONSTRAINT wl_users_user_id_key UNIQUE (user_id);


--
-- TOC entry 12 (OID 73130376)
-- Name: wl_week_pkey; Type: CONSTRAINT; Schema: public; Owner: A995847_fin
--

ALTER TABLE ONLY wl_week
    ADD CONSTRAINT wl_week_pkey PRIMARY KEY (id);


--
-- TOC entry 14 (OID 73130397)
-- Name: wl_day_pkey; Type: CONSTRAINT; Schema: public; Owner: A995847_fin
--

ALTER TABLE ONLY wl_day
    ADD CONSTRAINT wl_day_pkey PRIMARY KEY (id);


--
-- TOC entry 15 (OID 73130410)
-- Name: wl_food_item_pkey; Type: CONSTRAINT; Schema: public; Owner: A995847_fin
--

ALTER TABLE ONLY wl_item_type
    ADD CONSTRAINT wl_food_item_pkey PRIMARY KEY (id);


--
-- TOC entry 13 (OID 73130451)
-- Name: wl_week_wl_user_id_key; Type: CONSTRAINT; Schema: public; Owner: A995847_fin
--

ALTER TABLE ONLY wl_week
    ADD CONSTRAINT wl_week_wl_user_id_key UNIQUE (wl_user_id, week_number);


--
-- TOC entry 17 (OID 73131176)
-- Name: wl_weight_xfre_pkey; Type: CONSTRAINT; Schema: public; Owner: A995847_fin
--

ALTER TABLE ONLY wl_weight_xfre
    ADD CONSTRAINT wl_weight_xfre_pkey PRIMARY KEY (id);


--
-- TOC entry 16 (OID 73131182)
-- Name: wl_weight_xfre_date_key; Type: CONSTRAINT; Schema: public; Owner: A995847_fin
--

ALTER TABLE ONLY wl_weight_xfre
    ADD CONSTRAINT wl_weight_xfre_date_key UNIQUE (date, wl_user_id);


--
-- TOC entry 24 (OID 73130425)
-- Name: $1; Type: FK CONSTRAINT; Schema: public; Owner: A995847_fin
--

ALTER TABLE ONLY wl_item_type
    ADD CONSTRAINT "$1" FOREIGN KEY (wl_day_id) REFERENCES wl_day(id);


--
-- TOC entry 23 (OID 73130429)
-- Name: $1; Type: FK CONSTRAINT; Schema: public; Owner: A995847_fin
--

ALTER TABLE ONLY wl_day
    ADD CONSTRAINT "$1" FOREIGN KEY (wl_week_id) REFERENCES wl_week(id);


--
-- TOC entry 22 (OID 73130440)
-- Name: $1; Type: FK CONSTRAINT; Schema: public; Owner: A995847_fin
--

ALTER TABLE ONLY wl_week
    ADD CONSTRAINT "$1" FOREIGN KEY (wl_user_id) REFERENCES wl_users(id);


--
-- TOC entry 25 (OID 73131178)
-- Name: $1; Type: FK CONSTRAINT; Schema: public; Owner: A995847_fin
--

ALTER TABLE ONLY wl_weight_xfre
    ADD CONSTRAINT "$1" FOREIGN KEY (wl_user_id) REFERENCES wl_users(id);



